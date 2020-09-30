<?php

namespace Nurschool\Controller\CRUD;

use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use EasyCorp\Bundle\EasyAdminBundle\Exception\InsufficientEntityPermissionException;
use EasyCorp\Bundle\EasyAdminBundle\Factory\EntityFactory;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Security\Permission;
use Nurschool\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Nurschool\Event\RegisteredUserEvent;
use Nurschool\Event\UserProfileEvent;
use Nurschool\Form\ChangePasswordFormType;
use Nurschool\Form\ProfileFormType;
use Nurschool\Model\UserInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
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
     * @param Request $request
     * @param EventDispatcherInterface $eventDispatcher
     * @return Response
     */
    public function profile(Request $request, EventDispatcherInterface $eventDispatcher): Response
    {
        $user = $this->getUser();

        $profileForm = $this->createForm(ProfileFormType::class, $user);
        $event = new UserProfileEvent($request, $profileForm);
        $eventDispatcher->dispatch($event, UserProfileEvent::UPDATE_USER_PROFILE);

        if (null !== ($response = $event->getResponse())) {
            return $response;
        }

        $changePasswordForm = $this->createForm(ChangePasswordFormType::class, $user);
        $event = new UserProfileEvent($request, $changePasswordForm);
        $eventDispatcher->dispatch($event, UserProfileEvent::CHANGE_USER_PASSWORD);

        if (null !== ($response = $event->getResponse())) {
            return $response;
        }

        return $this->render('@EasyAdmin/user/profile.html.twig', [
            'profileForm' => $profileForm->createView(),
            'changePasswordForm' => $changePasswordForm->createView()
        ]);
    }
}
