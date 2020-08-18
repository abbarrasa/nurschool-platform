<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Form\Type;

use Nurschool\Validator\Constraints\Password;
use FOS\UserBundle\Form\Type\RegistrationFormType as FOSUserRegistrationFormType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotNull;

class RegistrationFormType extends FOSUserRegistrationFormType
{
    /** @var UrlGeneratorInterface */
    private $router;

    public function __construct(UrlGeneratorInterface $router, $class)
    {
        parent::__construct($class);
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'form.email'],
                'translation_domain' => 'FOSUserBundle'
            ])
            ->add('username', null, [
                'label' => false,
                'attr' => ['placeholder' => 'form.username'],
                'translation_domain' => 'FOSUserBundle'
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                    ],
                ],
                'first_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'form.password'
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'form.password_confirmation'
                    ]
                ],
                'invalid_message' => 'fos_user.password.mismatch',
                'constraints' => new Password(),
                'help' => 'form.password_help',
                'translation_domain' => 'FOSUserBundle'
            ])
//            ->add('roles', ChoiceType::class, [
//                'label' => 'form.roles',
//                'choices' => [
//                    'form.option.nurse' => 'ROLE_NURSE'
//                ],
//                'multiple' => true,
//                'expanded' => true,
//                'required' => false,
//                'translation_domain' => 'FOSUserBundle',
//                'choice_translation_domain' => 'FOSUserBundle'
//            ])
            ->add('agreeTerms', BooleanType::class, [
                'mapped' => false,
                'required' => false,
                'widget' => BooleanType::WIDGET_SWITCH,
                'label' => 'form.terms',
//                'label_translation_parameters' => [ '%link%' => $this->router->generate('terms') ],
                'label_translation_parameters' => [ '%link%' => $this->router->generate('home') ],
                'translation_domain' => 'FOSUserBundle',
                'constraints' => [
                    new NotNull(['message' => 'terms.rejected']),
                    new IsTrue(['message' => 'terms.rejected'])
                ]
            ])
        ;
    }
}