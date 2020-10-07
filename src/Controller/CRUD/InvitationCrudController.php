<?php

namespace Nurschool\Controller\CRUD;

use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Nurschool\Entity\Invitation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Nurschool\Field\RoleListField;
use Nurschool\Form\Type\RoleListType;

class InvitationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Invitation::class;
    }

    public function createEntity(string $entityFqcn)
    {
        return parent::createEntity($entityFqcn)
            ->setHost($this->getUser())
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email'),
            TextField::new('firstname'),
            TextField::new('lastname'),
            RoleListField::new('roles')
//            ChoiceField::new('roles')
//                ->setCustomOption(ChoiceField::OPTION_ALLOW_MULTIPLE_CHOICES, true)
//                ->setCustomOption(ChoiceField::OPTION_RENDER_EXPANDED, true)
////                ->setFormType(RoleListType::class)
//                ->setChoices(RoleListType::getRoleList())
        ];
    }
}
