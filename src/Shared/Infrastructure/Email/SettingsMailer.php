<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Shared\Infrastructure\Email;


use Nurschool\Shared\Domain\Service\Email\SettingsMailerInterface;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingsMailer implements SettingsMailerInterface
{
    private $settings;

    public function __construct(array $configMailer)
    {
        $this->loadSettings($configMailer);
    }

    public function loadSettings(array $settings)
    {
        $resolver = new OptionsResolver();
        $this->configureSettings($resolver);
        $this->settings = $resolver->resolve($settings);
    }

    public function setSetting(string $name, $value)
    {

    }

    public function getSetting(string $name)
    {
        $keys = explode('.', $name);
        return array_reduce($keys, function($a, $b) {
            if (!array_key_exists($b, $a)) {
                throw new MissingOptionsException(sprintf('"%s" parameter is not defined', $b));
            }

            return $a[$b];
        }, $this->settings);
    }

    protected function configureSettings(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired([
                'default_address', 'default_name', 'confirmation'
            ])
            ->setDefault('sandbox', false)
            ->setDefault('confirmation', function (OptionsResolver $confirmationResolver, Options $parent) {
                $confirmationResolver
                    ->setRequired('template')
                    ->setDefaults([
                        'address' => $parent['default_address'],
                        'name' => $parent['default_name']
                    ])
                ;
            })
        ;
    }
}