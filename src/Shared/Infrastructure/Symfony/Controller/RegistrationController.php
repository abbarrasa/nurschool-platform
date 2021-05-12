<?php

namespace Nurschool\Shared\Infrastructure\Symfony\Controller;

use Nurschool\Core\Infrastructure\Persistence\Doctrine\Repository\UserDoctrineRepository;
use Nurschool\Shared\Infrastructure\Symfony\Controller\Traits\ApiAwareTrait;
use Nurschool\Shared\Infrastructure\Symfony\Security\EmailVerifier;
use Nurschool\Shared\Infrastructure\Symfony\Validator\Constraints\Password;
use Nurschool\User\Application\Command\Create\CreateUserCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    use ApiAwareTrait;

    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @return Response
     */
    public function register(Request $request): Response
    {
//        $user = new User();
//        $form = $this->createForm(RegistrationFormType::class, $user);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $email = $form->get('email')->getData();
//            $password = $form->get('plainPassword')->getData();

        if ($request->isMethod(Request::METHOD_GET)) {
            return $this->render('registration/register.html.twig');
        }

        $this->validateRequest($request);
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $this->dispatch(new CreateUserCommand($email, $password));

        return $this->render('registration/confirmation.html.twig');

//            // encode the plain password
//            $user->setPassword(
//                $passwordEncoder->encodePassword(
//                    $user,
//                    $form->get('plainPassword')->getData()
//                )
//            );
//
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($user);
//            $entityManager->flush();
//
//            // generate a signed url and email it to the user
//            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
//                (new TemplatedEmail())
//                    ->from(new Address('admin@nurschool.es', 'Nurchool Mail Bot'))
//                    ->to($user->getEmail())
//                    ->subject('Please Confirm your Email')
//                    ->htmlTemplate('registration/confirmation_email.html.twig')
//            );
//            // do anything else you need here, like send an email
//
//            return $this->redirectToRoute('_preview_error');
//        }
//
//        return $this->render('registration/register.html.twig', [
//            'registrationForm' => $form->createView(),
//        ]);
    }

    /**
     * @Route("/register/confirm", name="register_confirm")
     */
    public function confirm(Request $request, UserDoctrineRepository $userDoctrineRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userDoctrineRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }


    private function validateRequest(Request $request): ConstraintViolationListInterface
    {
        $constraint = new Assert\Collection(
            [
                'email'    => [new Assert\NotBlank(), new Assert\Email()],
                'password' => [new Assert\NotBlank(), new Password()],
            ]
        );

        $input = $request->request->all();

        return Validation::createValidator()->validate($input, $constraint);
    }
}
