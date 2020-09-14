<?php

namespace Nurschool\Form;


use Nurschool\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class WelcomeUserProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Firstname',
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Lastname',
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add(
                'roles', ChoiceType::class, [
                    'label' => 'Roles',
                    'required' => true,
                    'choices' => ['Administrador' => 'ROLE_ADMIN', 'Enfermera' => 'ROLE_NURSE'],
                    'expanded' => true,
                    'multiple' => true,
                    'constraints' => [
                        new NotNull()
                    ],
                    'help' => 'aadfasdfsdfsdffds'
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}