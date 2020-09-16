<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Form;


use Doctrine\ORM\EntityRepository;
use Nurschool\Entity\School;
use Nurschool\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class WelcomeConfigAdminFormType extends AbstractType
{
    protected $security;
    
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('nurses', EntityType::class, [
                'label' => 'Nurses',
                'mapped' => false,
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->findByRole('ROLE_NURSE');
                },
                'choice_label' => function($user) {
                    return "{$user->getLastname()}, {$user->getFirstname()}";
                },
                'data' => $this->security->isGranted('ROLE_NURSE') ? $this->security->getUser() : null 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => School::class,
        ]);
    }
}