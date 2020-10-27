<?php

namespace Nurschool\Controller\CRUD;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Nurschool\Entity\School;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Nurschool\Filter\LocalityAdminLevelFilter;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class SchoolCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return School::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->andWhere(':user MEMBER OF entity.admins')
            ->setParameter('user', $this->getUser())
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('address')->onlyOnForms(),
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
            Field::new('locality.adminLevel.name')->hideOnForm(),
            AssociationField::new('nurses')
                ->onlyOnForms()
                ->setFormTypeOption('query_builder', function (EntityRepository $er) {
                    return $er->queryByRole('ROLE_NURSE');
                })
                ->setFormTypeOption('choice_label', function($user) {
                    return "{$user->getLastname()}, {$user->getFirstname()}";
                })
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('name')
            ->add('address')
            ->add('locality')
        ;
    }
}
