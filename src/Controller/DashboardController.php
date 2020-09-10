<?php

namespace Nurschool\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Nurschool\Mailer\MailerInterface;
use Nurschool\Model\UserInterface;
use Nurschool\Security\EmailVerifier;
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
     * @return Response
     */
    public function welcome(): Response
    {
        if (!$this->getUser()->isVerified()) {
            return $this->render('@EasyAdmin/verification_required.html.twig');
        }

        return $this->render('@EasyAdmin/welcome.html.twig');
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
        // yield MenuItem::linkToCrud('The Label', 'icon class', EntityClass::class);
    }
}
