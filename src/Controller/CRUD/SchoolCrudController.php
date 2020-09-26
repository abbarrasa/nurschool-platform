<?php

namespace Nurschool\Controller\CRUD;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Nurschool\Entity\School;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Nurschool\Entity\User;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SchoolCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return School::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('address')
                ->onlyOnForms(),
            ImageField::new('logoFile')
                ->setFormType(VichImageType::class)
                ->onlyOnForms(),
            ImageField::new('logo')
                ->setBasePath($this->getParameter('nurschool.path.school_images'))
                ->hideOnForm()
                ->setSortable(false),
            Field::new('locality.name')
                ->hideOnForm()
                ->setSortable(true),
            Field::new('locality.adminLevel.name')
                ->hideOnForm()
                ->setSortable(true)
        ];
    }
}
