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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BooleanType extends AbstractType
{
    const WIDGET_SWITCH = 'switch';
    const WIDGET_RADIO = 'radio';
    const WIDGET_SELECT = 'select';

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('widget')
            ->setDefault('widget', self::WIDGET_SELECT)
            ->setAllowedValues('widget', [self::WIDGET_SWITCH, self::WIDGET_RADIO, self::WIDGET_SELECT])
            ->setNormalizer(
                'expanded',
                function (Options $options, $value) {
                    return $options['widget'] === self::WIDGET_RADIO;
                }
            )
            ->setNormalizer(
                'multiple',
                function () {
                    return false;
                }
            )
            ->setNormalizer(
                'choices',
                function () {
                    return ['Sí' => true, 'No' => false];
                }
            )
        ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['widget'] = $options['widget'];
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}