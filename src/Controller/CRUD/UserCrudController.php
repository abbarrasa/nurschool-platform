<?php

namespace Nurschool\Controller\CRUD;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Nurschool\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Nurschool\Model\UserManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserCrudController extends AbstractCrudController
{
//    protected $manager;
//
//    public function __construct(UserManagerInterface $manager)
//    {
//        $this->manager = $manager;
//    }
//
    public static function getEntityFqcn(): string
    {
        return User::class;
    }
//
//    public function createNewUserEntity()
//    {
//        return $this->manager->createUser();
//    }
//
//    public function persistUserEntity($user)
//    {
//        $this->manager->updateUser($user);
//        parent::persistEntity($user);
//    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */

//    public function configureActions(Actions $actions): Actions
//    {
//        $profile = Action::new('profile', 'Profile', 'fa fa-id-card')
//            ->linkToRoute('fos_user_profile_edit')
//        ;
//
        // this action executes the 'renderInvoice()' method of the current CRUD controller
//        $viewInvoice = Action::new('viewInvoice', 'Invoice', 'fa fa-file-invoice')
//            ->linkToCrudAction('renderInvoice');

        // if the method is not defined in a CRUD controller, link to its route
//        $sendInvoice = Action::new('sendInvoice', 'Send invoice', 'fa fa-envelope')
//            // if the route needs parameters, you can define them:
//            // 1) using an array
//            ->linkToRoute('invoice_send', [
//                'send_at' => (new \DateTime('+ 10 minutes'))->format('YmdHis'),
//            ])

            // 2) using a callable (useful if parameters depend on the entity instance)
            // (the type-hint of the function argument is optional but useful)
//            ->linkToRoute('invoice_send', function (Invoice $entity) {
//                return [
//                    'uuid' => $entity->getId(),
//                    'method' => $entity->sendMethod(),
//                ];
//            });

        // this action points to the invoice on Stripe application
//        $viewStripeInvoice = Action::new('viewInvoice', 'Invoice', 'fa fa-file-invoice')
//            ->linkToUrl(function (Invoice $entity) {
//                return 'https://www.stripe.com/invoice/'.$entity->getStripeReference();
//            });

//        return $actions
            // ...
//            ->add(Crud::PAGE_DETAIL, $viewInvoice)
//            ->add(Crud::PAGE_DETAIL, $sendInvoice)
//            ->add(Crud::PAGE_DETAIL, $viewStripeInvoice)
//            ->add(Crud::PAGE_DETAIL, $profile)
//        ;
//    }

//    /**
//     * Profile user.
//     *
//     * @param Request $request
//     * @return Response|null
//     */
//    public function profile(Request $request)
//    {
//        $changePasswordResponse = $this->forward('fos_user.change_password.controller:changePasswordAction', [$request]);
//        $editProfileResponse = $this->forward('fos_user.profile.controller:editAction', [$request]);
//
//        return $this->render('@FOSUser/Profile/profile.html.twig', [
//            'change_password' => $changePasswordResponse->getContent(),
//            'edit_profile' => $editProfileResponse->getContent()
//        ]);
//    }
}
