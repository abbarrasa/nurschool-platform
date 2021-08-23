<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Shared\Infrastructure\Symfony\Email;


use Nurschool\Shared\Domain\Service\Email\SettingsMailerInterface;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendGridSettingsMailer implements SettingsMailerInterface
{
    private $settings;

    public function __construct(array $settings)
    {
        $this->loadSettings($settings);
    }

    /**
     * Loads Sendgrid mailer settings
     * @param array $settings
     * @return mixed|void
     */
    public function loadSettings(array $settings)
    {
        $resolver = new OptionsResolver();
        $this->configureSettings($resolver);
        $this->settings = $resolver->resolve($settings);
    }

    /**
     * Sets a Sendgrid mailer setting value. For nested properties, name format shoulds be property1.property2.property3
     * where:
     * Array(
     *      [property1] => Array(
     *          [property2] => Array(
     *              [property3] => value
     *          )
     * )
     * @param string $name
     * @param $value
     * @return void
     */
    public function setSetting(string $name, $value)
    {
        $keys = explode('.', $name);
        $current = &$this->settings;
        foreach ($keys as $key) {
            if (!isset($current[$key])) {
                $current[$key] = [];
            }
            $current = &$current[$key];
        }

        $current = $value;
    }

    /**
     * Gets a Sendgrid mailer setting value by its name. For nested properties, name format shoulds be
     * property1.property2.property3 where:
     * Array(
     *      [property1] => Array(
     *          [property2] => Array(
     *              [property3] => value
     *          )
     * )
     * @param string $name
     * @return mixed|void
     */
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

    /**
     * Gets all Sendgrid mailer settings
     * @return mixed
     */
    public function getAllSettings()
    {
        return $this->settings;
    }

    /**
     * Configures Sendgrid mailer setting options
     * @param OptionsResolver $resolver
     */
    protected function configureSettings(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired([
                'default_address', 'default_name', 'email_confirmation'
            ])
            ->setDefaults([
                'sandbox' => false,
                'disable_delivery' => false,
                'redirect_to' => false,
                'redirect_to_address' => function (Options $options) {
                    return $options['default_address'];
                },
                'email_confirmation' => function (OptionsResolver $resolver, Options $parent) {
                    $resolver
                        ->setRequired(['template', 'subject'])
                        ->setDefaults([
                            'address' => $parent['default_address'],
                            'name' => $parent['default_name']
                        ])
                    ;
                },
//                'resetting' => function (OptionsResolver $resolver, Options $parent) {
//                    $resolver
//                        ->setRequired(['template', 'subject'])
//                        ->setDefaults([
//                            'address' => $parent['default_address'],
//                            'name' => $parent['default_name']
//                        ])
//                    ;
//                },
//                'invitation' => function (OptionsResolver $resolver, Options $parent) {
//                    $resolver
//                        ->setRequired(['template', 'subject'])
//                        ->setDefaults([
//                            'address' => $parent['default_address'],
//                            'name' => $parent['default_name']
//                        ])
//                    ;
//                }
            ])
        ;
    }
}