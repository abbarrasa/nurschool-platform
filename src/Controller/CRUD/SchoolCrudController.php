<?php

namespace Nurschool\Controller\CRUD;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Nurschool\Entity\School;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
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
//            IdField::new('id'),
//            TextField::new('title'),
//            TextEditorField::new('description'),
            TextField::new('name'),
            ImageField::new('logo')
                ->setBasePath($this->getParameter('nurschool.path.school_images'))
                ->hideOnForm(),
            ImageField::new('logoFile')
                ->setFormType(VichImageType::class)
                ->onlyOnForms()

        ];
    }
}
