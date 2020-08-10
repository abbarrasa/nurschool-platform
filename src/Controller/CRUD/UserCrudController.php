<?php

namespace Nurschool\Controller\CRUD;

use FOS\UserBundle\Model\UserManagerInterface;
use Nurschool\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    protected $manager;

    public function __construct(UserManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function createNewUserEntity()
    {
        return $this->manager->createUser();
    }

    public function persistUserEntity($user)
    {
        $this->manager->updateUser($user, false);
        parent::persistEntity($user);
    }

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
}
