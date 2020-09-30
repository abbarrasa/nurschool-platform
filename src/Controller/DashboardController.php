<?php

namespace Nurschool\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Security\Permission;
use Nurschool\Entity\Enquiry;
use Nurschool\Entity\School;
use Nurschool\Entity\User;
use Nurschool\EventListener\WelcomeStageContainer;
use Nurschool\Mailer\MailerInterface;
use Nurschool\Manager\AvatarManager;
use Nurschool\Model\UserInterface;
use Nurschool\Security\EmailVerifier;
use Nurschool\Wizard\Container\StageContainerInterface;
use Nurschool\Wizard\Stage\FormHandlerInterface;
use Nurschool\Wizard\Stage\WizardCompleteInterface;
use Nurschool\Wizard\Wizard;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard")
 */
class DashboardController extends AbstractDashboardController
{
    /** @var AvatarManager */
    protected $avatarManger;

    public function __construct(AvatarManager $avatarManager)
    {
        $this->avatarManger = $avatarManager;
    }

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
     * @Route("/welcome/{stage}", name="welcome_stage")
     * @param Request $request
     * @param StageContainerInterface $stageContainer
     * @param null $stage
     * @return Response
     * @throws \Symfony\Component\Config\Exception\LoaderLoadException
     */
    public function welcome(Request $request, StageContainerInterface $stageContainer, $stage = null): Response
    {
        /** @var UserInterface $user */
        $user = $this->getUser();
        if (!$user->isVerified()) {
            return $this->render('@EasyAdmin/verification_required.html.twig');
        }

        if ($user->isConfigured()) {
            return $this->redirectToRoute('dashboard');
        }

        if (null === $stage) {
            return $this->render('@EasyAdmin/welcome.html.twig');
        }

        // begin the wizard
        $wizard = new Wizard($stageContainer, $this->getParameter('kernel.project_dir') . '/config/stages/welcome_stages.yaml');
        $currentStage = $wizard->getCurrentStage($stage);
        if ($stage !== $currentStage->getName()) {
            return $this->redirectToRoute('welcome_stage', ['stage' => $currentStage->getName()]);
        }

        if ($currentStage instanceof WizardCompleteInterface) {
            $this->addFlash('success', 'Cuenta configurada satisfactoriamente. Ya puedes usar nurschool.');
            return $currentStage->getResponse($request);
        }

        $templateParams = $currentStage->getTemplateParams();
        if ($wizard->isHalted()) {
            $this->addFlash('danger', $wizard->getWarning());
            return $this->render('@EasyAdmin/welcome.html.twig');
        }

        // handle the form
        if ($currentStage instanceof FormHandlerInterface) {
            $form = $currentStage->getFormType();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $currentStage->handleFormResult($form);
                return $this->redirectToRoute('welcome_stage', ['stage' => $wizard->getNextStage()->getName()]);
            }
            $templateParams['form'] = $form->createView();
        }

        return $this->render($currentStage->getTemplateName(), $templateParams);
    }

    /**
     * @Route("/welcome/resend-confirmation", name="resend_confirmation_email")
     * @param EmailVerifier $emailVerifier
     * @param MailerInterface $mailer
     * @return Response
     */
    public function resendConfirmationEmail(EmailVerifier $emailVerifier, MailerInterface $mailer): Response
    {
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
            yield MenuItem::linkToCrud('Schools', 'fa fa-school', School::class);
        }

        yield MenuItem::linkToCrud('Users', 'fa fa-users', User::class);
        // yield MenuItem::linkToCrud('The Label', 'icon class', EntityClass::class);
    }

    public function configureUserMenu(\Symfony\Component\Security\Core\User\UserInterface $user): UserMenu
    {
        $userMenuItems = [
            MenuItem::linktoRoute('My profile', 'fa fa-id-card', 'user_profile'),
            MenuItem::linkToLogout('__ea__user.sign_out', 'fa-sign-out')
        ];
        if ($this->isGranted(Permission::EA_EXIT_IMPERSONATION)) {
            $userMenuItems[] = MenuItem::linkToExitImpersonation('__ea__user.exit_impersonation', 'fa-user-lock');
        }

        return UserMenu::new()
            ->displayUserName()
            ->displayUserAvatar()
            ->setName(method_exists($user, '__toString') ? (string) $user : $user->getUsername())
            ->setAvatarUrl( $this->avatarManger->getAvatarUrl($user))
            ->setMenuItems($userMenuItems);
    }

}
