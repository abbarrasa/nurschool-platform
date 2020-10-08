<?php

namespace Nurschool\Controller;

use Nurschool\Entity\Invitation;
use Nurschool\Entity\User;
use Nurschool\Event\RegisteredUserEvent;
use Nurschool\Form\RegistrationFormType;
use Nurschool\Manager\AvatarManager;
use Nurschool\Security\EmailVerifier;
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
    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    /** @var EventDispatcherInterface  */
    private $eventDispatcher;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EventDispatcherInterface $eventDispatcher)
    {
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
        $user = new User();
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
     * @Route("/invitation/{code}", name="invitation")
     * @param Request $request
     * @param string|null $code
     * @return Response
     */
    public function invitation(Request $request, string $code = null): Response
    {
        if ($code) {
            // We store the invitation code in session and remove it from the URL, to avoid the
            // URL being loaded in a browser and potentially leaking the code to 3rd party
            // JavaScript.
            $this->storeCodeInSession($request, $code);

            return $this->redirectToRoute('invitation');
        }

        if (null === ($code = $this->getCodeFromSession($request))) {
            throw $this->createNotFoundException('No invitation code found in the URL or in the session.');
        }

        $repository = $this->getDoctrine()->getRepository(Invitation::class);
        if (null === ($invitation = $repository->findOneBy(['code' => $code]))) {
            throw $this->createNotFoundException('No invitation found for this code.');
        }

        $user = new User();
        $user->setInvitation($invitation);

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setFirstname($user->getInvitation()->getFirstname());
            $user->setLastname($user->getInvitation()->getLastname());
            $user->setRoles($user->getInvitation()->getRoles());
            $user->setIsVerified(true);

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

            $this->addFlash('success', 'Your registration has been successful.');
            return $this->redirectToRoute('welcome');
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
        $session = $request->getSession();
        $email = $session->get('SendConfirmationEmail');
        $expiresAt = $session->get('SendConfirmationTokenExpiresAt');

        if (empty($email)) {
            return $this->redirectToRoute('register');
        }

        $session->remove('SendConfirmationEmail');
        $session->remove('SendConfirmationTokenExpiresAt');
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (null === $user) {
            return $this->redirectToRoute('login');
        }

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

    private function storeCodeInSession(Request $request, string $code): void
    {
        $request->getSession()->set('InvitationPublicCode', $code);
    }

    private function getCodeFromSession(Request $request): ?string
    {
        $request->getSession()->get('InvitationPublicCode');
    }
}