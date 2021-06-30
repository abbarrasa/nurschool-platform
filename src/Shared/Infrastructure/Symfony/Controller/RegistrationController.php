<?php

namespace Nurschool\Shared\Infrastructure\Symfony\Controller;

use Nurschool\Core\Infrastructure\Persistence\Doctrine\Repository\UserDoctrineRepository;
use Nurschool\Shared\Infrastructure\Symfony\Controller\Traits\ApiAwareTrait;
use Nurschool\Shared\Infrastructure\Symfony\Form\RegistrationFormType;
use Nurschool\Shared\Infrastructure\Symfony\Security\EmailVerifier;
use Nurschool\Shared\Infrastructure\Symfony\Validator\Constraints\Password;
use Nurschool\User\Application\Command\Create\CreateUserCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends WebController
{
    use ApiAwareTrait;

    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @return Response
     */
    public function register(Request $request): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $plainPassword = $form->get('password')->getData();

            $command = new CreateUserCommand($email);
            $command->plainPassword = $plainPassword;

            $this->dispatch($command);

//            $this->dispatch(CreateUserCommand::create($email, $plainPassword));

            return $this->render('registration/confirmation.html.twig');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView()
        ]);

//        if ($request->isMethod(Request::METHOD_GET)) {
//            return $this->render('registration/register.html.twig');
//        }
//
//        $violations = $this->validateRequest($request);
//        if ($violations->count()) {
//            return $this->redirectWithErrors($request, $violations, 'register');
//        }
//
//        $email = $request->request->get('email');
//        $password = $request->request->get('password');
//
//        $this->dispatch(new CreateUserCommand($email, $password));
//
//        return $this->render('registration/confirmation.html.twig');
    }

    /**
     * @Route("/register/confirm", name="register_confirm")
     */
    public function confirm(Request $request, EmailVerifier $emailVerifier): Response
    {
        try {
            $emailVerifier->handleSignedUrl($request);

            // @TODO Change the redirect on success and handle or remove the flash message in your templates
            $this->addFlash('success', 'Your email address has been verified.');

            return $this->redirectToRoute('home');
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('register');
        } catch(\Exception $exception) {
            return $this->redirectToRoute('register');
        }
    }

    private function validateRequest(Request $request): ConstraintViolationListInterface
    {
        $constraint = new Assert\Collection(
            [
                'email'    => [new Assert\NotBlank(), new Assert\Email()],
                'password' => [new Assert\NotBlank(), new Password()],
                'aggrement' => [new Assert\NotNull(), new Assert\IsTrue()]
            ]
        );

        $input = $request->request->all();

        return Validation::createValidator()->validate($input, $constraint);
    }
}
