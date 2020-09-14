<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Form\Factory;



use Nurschool\Entity\School;
use Nurschool\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class WelcomeFormFactory
{
    /** @var Security */
    protected $security;

    /** @var FormFactory */
    protected $factory;

    public function __construct(Security $security, FormFactoryInterface $factory)
    {
        $this->security = $security;
        $this->factory = $factory;
    }

    /**
     * @return FormInterface
     */
    public function createWelcomeUserProfileForm(): FormInterface
    {
        $form = $this->factory->create()
            ->setData($this->security->getUser())
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

        return $form;
    }

    public function createWelcomeConfigForm(): FormInterface
    {
        $form = $this->factory->create();
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $form
                ->setData(new School())
                ->add('name', TextType::class, [
                    'label' => 'Name',
                    'required' => true,
                    'constraints' => [
                        new NotBlank()
                    ]
                ])
                ->add('users', EntityType::class, [
                    'label' => 'Nurses',
                    'class' => User::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->where('u.roles LIKE :role')
                            ->setParameter('role', "%ROLE_NURSE%")
                            ->orderBy('u.lastname', 'ASC');
                    },
                    'choice_label' => function($user) {
                        return "{$user->getLastname()}, {$user->getFirstname()}";
                    }
                ])
            ;
        } elseif ($this->security->isGranted('ROLE_NURSE')) {
            $form
                ->setData($this->security)
                ->add('schools', EntityType::class, [
                    'label' => 'School',
                    'class' => School::class,
                    'choice_label' => 'name'
                ])
            ;
        } else {
            throw new \Exception('No role');
        }

        return $form;
    }
}