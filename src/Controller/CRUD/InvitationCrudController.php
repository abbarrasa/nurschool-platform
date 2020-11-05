<?php

namespace Nurschool\Controller\CRUD;

use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Nurschool\Entity\Invitation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Nurschool\Field\RoleListField;
use Symfony\Component\Validator\Constraints\NotNull;

class InvitationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Invitation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            'email',
            'firstname',
            'lastname',
            RoleListField::new('roles')
                ->renderAsBadges()
                ->setFormTypeOption('constraints', [new NotNull()]),
            AssociationField::new('schools')
                ->setRequired(true)
                ->onlyOnForms()
                ->setFormTypeOption('query_builder', function (EntityRepository $er) {
                    return $er->getQueryByUserAdmin($this->getUser());
                })
                ->setFormTypeOption('choice_label', function($school) {
                    return $school->getName();
                })
        ];
    }
}
