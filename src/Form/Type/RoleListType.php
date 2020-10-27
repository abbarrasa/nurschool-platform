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


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleListType extends AbstractType
{
    private const ROLE_LIST = [
        'Administrador' => 'ROLE_ADMIN',
        'Enfermera' => 'ROLE_NURSE'
    ];

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                // make expanded default value
                'expanded' => true,
                'multiple' => true,
                'choices' => self::ROLE_LIST,
                'choice_translation_domain' => static function (Options $options, $value) {
                    // if choice_translation_domain is true, then it's the same as translation_domain
                    if (true === $value) {
                        $value = $options['translation_domain'];
                    }

                    if (null === $value) {
                        return 'Nurschool';
                    }

                    return $value;
                },
                'help' => 'aadfasdfsdfsdffds'
            ])
        ;
    }


    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}