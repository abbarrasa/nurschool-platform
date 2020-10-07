<?php

namespace Nurschool\Controller\CRUD;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Nurschool\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Nurschool\Event\UserProfileEvent;
use Nurschool\Form\ChangePasswordFormType;
use Nurschool\Form\ProfileFormType;
use Nurschool\Model\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function(Action $action) {
                return $action
                    ->linkToUrl(
                        $this->get(CrudUrlGenerator::class)
                            ->build()
                            ->setController(InvitationCrudController::class)
                            ->setAction(Action::NEW)
                            ->generateUrl()
                    );
            })
//
//            ->add(
//                Crud::PAGE_NEW,
//                Action::new(Action::NEW, 'New', null)
//                    ->linkToUrl(
//                        $this->get(CrudUrlGenerator::class)
//                            ->build()
//                            ->setController(InvitationCrudController::class)
//                            ->setAction(Action::NEW)
//                            ->generateUrl()
//                    )
//            )
        ;

        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            EmailField::new('email'),
            TextField::new('firstname'),
            TextField::new('lastname'),
            ImageField::new('avatar')
                ->setBasePath($this->getParameter('nurschool.path.user_images'))
                ->hideOnForm()
                ->setSortable(false),
            BooleanField::new('isVerified')->renderAsSwitch(false),
            BooleanField::new('enabled')
        ];

        return $fields;
    }

    /**
     * @Route("/dashboard/profile", name="user_profile")
     * @param AdminContext $context
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function profile(AdminContext $context, Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->getUser();
        $tabsConfig = $this->buildProfileTabsConfig();

        $profileForm = $this->createForm(ProfileFormType::class, $user);
        $profileForm->handleRequest($request);
        if ($profileForm->isSubmitted()) {
            if ($profileForm->isValid()) {
                /** @var UserInterface $user */
                $user = $profileForm->getData();

                $this->saveUser($user);
                $this->addFlash('success', 'Has modificado tus datos correctamente.');
            }

            $tabsConfig['change-password']['active'] = false;
            $tabsConfig['profile']['active'] = true;
            $tabsConfig['profile']['errors'] = $profileForm->getErrors(true)->count();
        }

        $changePasswordForm = $this->createForm(ChangePasswordFormType::class, $user);
        $changePasswordForm->handleRequest($request);
        if ($changePasswordForm->isSubmitted()) {
            if ($changePasswordForm->isValid()) {
                /** @var UserInterface $user */
                $user = $changePasswordForm->getData();
                // encode the plain password
                $user->setPassword(
                    $this->passwordEncoder->encodePassword(
                        $user,
                        $changePasswordForm->get('plainPassword')->getData()
                    )
                );

                $this->saveUser($user);
                $this->addFlash('success', 'Has modificado tu contraseña correctamente.');
            }

            $tabsConfig['profile']['active'] = false;
            $tabsConfig['change-password']['active'] = true;
            $tabsConfig['change-password']['errors'] = $changePasswordForm->getErrors(true)->count();
        }

        return $this->render('@EasyAdmin/user/profile.html.twig', [
            'profileForm' => $profileForm->createView(),
            'changePasswordForm' => $changePasswordForm->createView(),
            'tabs_config' => $tabsConfig
        ]);
    }

    private function saveUser($user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }

    private function buildProfileTabsConfig(): array
    {
        return [
            'profile' => [
                'label' => 'Mis datos',
                'id' => 'profile',
                'active' => true,
                'errors' => 0
            ],
            'change-password' => [
                'label' => 'Cambiar contraseña',
                'id' => 'change-password',
                'active' => false,
                'errors' => 0
            ]
        ];
    }
}