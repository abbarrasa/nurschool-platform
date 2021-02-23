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


use Nurschool\Shared\Infrastructure\Email\SendGrid\SendGridSettingsMailer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class SendGridSettingsMailerTest extends TestCase
{
    public function testWithoutSettings()
    {
        $this->expectException(MissingOptionsException::class);
        $settingsMailer = new SendGridSettingsMailer([]);
    }

    public function testWithoutApiKey()
    {
        $settings = $this->generateSettings();
        unset($settings['api_key']);
        $this->expectExceptionMessage('The required option "api_key" is missing.');
        $settingsMailer = new SendGridSettingsMailer($settings);
    }

    public function testErrorGettingValue()
    {
        $settings = $this->generateSettings();
        $settingsMailer = new SendGridSettingsMailer($settings);

        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionMessage('"foo" parameter is not defined');
        $foo = $settingsMailer->getSetting('foo');
    }

    public function testSettings()
    {
        $settings = $this->generateSettings();
        $settingsMailer = new SendGridSettingsMailer($settings);

        $apiKey = $settingsMailer->getSetting('api_key');
        $this->assertEquals($settings['api_key'], $apiKey);
        $welcomeTemplate = $settingsMailer->getSetting('welcome.template');
        $this->assertEquals($settings['welcome']['template'], $welcomeTemplate);

        $newWelcomeTemplate = 'MyNewWelcomeTemplateId';
        $settingsMailer->setSetting('welcome.template', $newWelcomeTemplate);
        $welcomeTemplate = $settingsMailer->getSetting('welcome.template');
        $this->assertEquals($newWelcomeTemplate, $welcomeTemplate);

        $newApiKey = md5('mynewapikey');
        $settingsMailer->setSetting('api_key', $newApiKey);
        $apiKey = $settingsMailer->getSetting('api_key');
        $this->assertEquals($newApiKey, $apiKey);
    }

    private function generateSettings()
    {
        return [
            'api_key' => md5('thisismyapikey'),
            'sandbox' => true,
            'disable_delivery' => false,
            'redirect_to' => false,
            'default_address' => 'test@domain.com',
            'default_name' => 'My name',
            'welcome' => [
                'template' => 'WelcomeTemplateId',
                'subject' => 'Welcome to Nurschool'
            ],
            'resetting' => [
                'template' => 'ResettingTemplateId',
                'subject' => 'Reset your password'
            ],
            'invitation' => [
                'template' => 'InvitationTemplateId',
                'subject' => 'Invitation to join to Nurschool'
            ]
        ];
    }
}