<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Tests\Infrastructure\Email;


use Nurschool\Shared\Infrastructure\Symfony\Email\SymfonySettingsMailer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class SendGridSettingsMailerTest extends TestCase
{
    public function testWithoutSettings()
    {
        $this->expectException(MissingOptionsException::class);
        $settingsMailer = new SymfonySettingsMailer([]);
    }

    public function testErrorGettingValue()
    {
        $settings = $this->generateSettings();
        $settingsMailer = new SymfonySettingsMailer($settings);

        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionMessage('"foo" parameter is not defined');
        $foo = $settingsMailer->getSettingValue('foo');
    }

    public function testAllSettings()
    {
        $settings = $this->generateSettings();
        $settingsMailer = new SymfonySettingsMailer($settings);
        $sandbox = $settingsMailer->getSettingValue('sandbox');
        $this->assertEquals($settings['sandbox'], $sandbox);
        $disableDelivery = $settingsMailer->getSettingValue('disable_delivery');
        $this->assertEquals($settings['disable_delivery'], $disableDelivery);
        $redirectTo = $settingsMailer->getSettingValue('redirect_to');
        $this->assertEquals($settings['redirect_to'], $redirectTo);
        $defaultAddress = $settingsMailer->getSettingValue('default_address');
        $this->assertEquals($settings['default_address'], $defaultAddress);
        $defaultName = $settingsMailer->getSettingValue('default_name');
        $this->assertEquals($settings['default_name'], $defaultName);
        $confirmationTemplate = $settingsMailer->getSettingValue('email_confirmation.template');
        $this->assertEquals($settings['email_confirmation']['template'], $confirmationTemplate);
        $confirmationSubject = $settingsMailer->getSettingValue('email_confirmation.subject');
        $this->assertEquals($settings['email_confirmation']['subject'], $confirmationSubject);
        $confirmationAddress = $settingsMailer->getSettingValue('email_confirmation.address');
        $this->assertEquals($defaultAddress, $confirmationAddress);
        $confirmationName = $settingsMailer->getSettingValue('email_confirmation.name');
        $this->assertEquals($defaultName, $confirmationName);
    }

    private function generateSettings()
    {
        return [
            'sandbox' => true,
            'disable_delivery' => false,
            'redirect_to' => false,
            'default_address' => 'test@domain.com',
            'default_name' => 'My name',
            'email_confirmation' => [
                'template' => 'WelcomeTemplateId',
                'subject' => 'Welcome to Nurschool'
            ]
        ];
    }
}