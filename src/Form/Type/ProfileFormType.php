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


use FOS\UserBundle\Form\Type\ProfileFormType as FOSUserProfileFormType;
use Nurschool\Entity\Group;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ProfileFormType extends FOSUserProfileFormType
{
    protected function buildUserForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, [
                'label' => 'form.username',
                'translation_domain' => 'FOSUserBundle']
            )
            ->add('email', EmailType::class, [
                'label' => 'form.email',
                'translation_domain' => 'FOSUserBundle']
            )
            ->add('firstname', null, [
                'label' => 'form.firstname',
                'translation_domain' => 'FOSUserBundle']
            )
            ->add('lastname', null, [
                'label' => 'form.lastname',
                'translation_domain' => 'FOSUserBundle']
            )
            ->add('groups', EntityType::class, [
                'class' => Group::class,
                'choice_label' => 'name',
                'label' => 'form.groups',
                'multiple' => true,
                'expanded' => true,
                'translation_domain' => 'FOSUserBundle',
                'choice_translation_domain' => 'FOSUserBundle'
            ])
        ;
    }
}