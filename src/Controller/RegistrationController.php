<?php

namespace Nurschool\Controller;

use Nurschool\Controller\Traits\RegistrationControllerTrait;
use Nurschool\Entity\Invitation;
use Nurschool\Entity\User;
use Nurschool\Event\RegisteredUserEvent;
use Nurschool\Form\RegistrationFormType;
use Nurschool\Manager\AvatarManager;
use Nurschool\Manager\UserManager;
use Nurschool\Security\EmailVerifier;
use Nurschool\Security\InvitationHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    use RegistrationControllerTrait;

    /** @var UserManager */
    private $userManager;

    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    /** @var EventDispatcherInterface  */
    private $eventDispatcher;

    public function __construct(
        UserManager $userManager,
        UserPasswordEncoderInterface $passwordEncoder,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->userManager = $userManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Register an user.
     * @Route("/register", name="register")
     * @param Request $request
     * @return Response
     */
    public function register(Request $request): Response
    {
        $user = $this->userManager->createUser();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Launch an event when user account is created
            $this->eventDispatcher->dispatch(new RegisteredUserEvent($user), RegisteredUserEvent::NAME);

            return $this->redirectToRoute('register_done');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * Register an user via invitation.
     * @Route("/register/invitation/{token}", name="invitation")
     * @param Request $request
     * @param InvitationHelper $helper
     * @param string|null $token
     * @return Response
     * @throws \Nurschool\Security\Exception\ExpiredInvitationTokenException
     * @throws \Nurschool\Security\Exception\InvalidInvitationTokenException
     */
    public function invitation(Request $request, InvitationHelper $helper, string $token = null): Response
    {
        if ($token) {
            // We store the invitation code in session and remove it from the URL, to avoid the
            // URL being loaded in a browser and potentially leaking the code to 3rd party
            // JavaScript.
            $this->storeInvitationTokenInSession($token);

            return $this->redirectToRoute('invitation');
        }

        if (null === ($token = $this->getInvitationTokenFromSession())) {
            throw $this->createNotFoundException('No invitation token found in the URL or in the session.');
        }

        $invitation = $helper->validateTokenAndFetchInvitation($token);
        $user = $this->userManager->createUserFromInvitation($invitation);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setIsVerified(true);

            // encode the plain password
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $this->userManager->save($user);
            $this->cleanSessionAfterRegistration();

            $this->addFlash('success', 'Your registration has been successful.');
            return $this->redirectToRoute('register_done');
        }

        return $this->render('registration/invitation.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * Tell the user to check their email provider.
     * @Route("/register/done", name="register_done")
     * @param Request $request
     * @return RedirectResponse|Response|null
     */
    public function registerDone(Request $request)
    {
        if (!$this->checkConfirmationEmailInSession()) {
            return $this->redirectToRoute('dashboard');
        }

        $email = $this->getConfirmationEmailFromSession();
        if (empty($email)) {
            return $this->redirectToRoute('register');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (null === $user) {
            return $this->redirectToRoute('login');
        }

        $expiresAt = $this->getConfirmationTokenExpiresAtFromSession();
        $this->cleanSessionAfterConfirmation();

        return $this->render('registration/done.html.twig', [
            'user' => $user,
            'expiresAt' => $expiresAt
        ]);
    }

    /**
     * Verify an user account.
     * @Route("/verify/email", name="verify_email")
     * @param Request $request
     * @param EmailVerifier $emailVerifier
     * @return Response
     */
    public function verifyUserEmail(Request $request, EmailVerifier $emailVerifier): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('welcome');
    }
}