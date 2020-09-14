<?php

namespace Nurschool\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Nurschool\Entity\Enquiry;
use Nurschool\Entity\School;
use Nurschool\Form\Factory\WelcomeFormFactory;
use Nurschool\Form\WelcomeUserProfileFormType;
use Nurschool\Mailer\MailerInterface;
use Nurschool\Model\UserInterface;
use Nurschool\Security\EmailVerifier;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard")
 */
class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("", name="dashboard")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('@EasyAdmin/dashboard.html.twig', [
            'dashboard_controller_filepath' => (new \ReflectionClass(static::class))->getFileName(),
            'dashboard_controller_class' => (new \ReflectionClass(static::class))->getShortName(),
        ]);
    }

    /**
     * @Route("/welcome", name="welcome")
     * @param Request $request
     * @return Response
     */
    public function welcome(Request $request, WelcomeFormFactory $formFactory): Response
    {
        /** @var UserInterface $user */
        $user = $this->getUser();
        if (!$user->isVerified()) {
            return $this->render('@EasyAdmin/verification_required.html.twig');
        }

//        $form = $this->createForm(WelcomeFormType::class, $user);
        $form = $formFactory->createWelcomeUserProfileForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


        }

        return $this->render('@EasyAdmin/welcome.html.twig', [
            'profileForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/welcome/config", name="config")
     * @return Response
     */
    public function config(): Response
    {
        throw new \Exception('It is not implemented yet');
    }

    /**
     * @Route("/welcome/resend-confirmation", name="resend_confirmation_email")
     * @param EmailVerifier $emailVerifier
     * @param MailerInterface $mailer
     * @return Response
     */
    public function resendConfirmationEmail(EmailVerifier $emailVerifier, MailerInterface $mailer): Response
    {
        /** @var UserInterface $user */
        $user = $this->getUser();
        // generate a signed url and email it to the user
        $signatureComponents = $emailVerifier->generateSignatureConfirmation('verify_email', $user);
        $mailer->sendConfirmationEmail($user, $signatureComponents);

        $this->addFlash('success', sprintf('Se ha enviado el email de bienvenida a %s correctamente.', $user->getEmail()));

        return $this->redirectToRoute('welcome');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Nurschool');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            yield MenuItem::linkToCrud('Enquiries', 'fa fa-tags', Enquiry::class);
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::linkToCrud('Schools', 'fa fa-tags', School::class);
        }
        // yield MenuItem::linkToCrud('The Label', 'icon class', EntityClass::class);
    }
}
