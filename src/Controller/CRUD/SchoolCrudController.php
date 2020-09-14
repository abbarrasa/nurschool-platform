<?php

namespace Nurschool\Controller\CRUD;

use Nurschool\Entity\School;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SchoolCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return School::class;
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
