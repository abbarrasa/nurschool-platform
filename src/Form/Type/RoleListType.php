<?php

namespace Nurschool\Form\Type;


class RoleListType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // make expanded default value
            'expanded' => true,
            'choices' => function (Options $options, $parentChoices) {
                return ['Administrador' => 'ROLE_ADMIN', 'Enfermera' => 'ROLE_NURSE'];
            },
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
        ]);
    }


    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}