<?php

namespace Nurschool\Controller\CRUD;

use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Nurschool\Entity\Student;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Nurschool\Form\ContactFormType;

class StudentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Student::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstname'),
            TextField::new('lastname'),
            DateField::new('birthdate'),
            AssociationField::new('school')
                ->setRequired(true)
                ->setFormTypeOption('query_builder', function (EntityRepository $er) {
                    return $er->getQueryByUserNurse($this->getUser());
                })
                ->setFormTypeOption('choice_label', function($school) {
                    return $school->getName();
                }),
            CollectionField::new('contacts')
                ->allowAdd()
                ->allowDelete()
                ->setEntryIsComplex(true)
                ->setEntryType(ContactFormType::class)
                ->setFormTypeOption('by_reference', false)
        ];
    }
}
