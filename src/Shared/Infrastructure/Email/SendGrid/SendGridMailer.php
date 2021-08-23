<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Nurschool\Shared\Infrastructure\Email\SendGrid;


use Nurschool\Shared\Domain\Service\Email\MailerInterface;
use Nurschool\Shared\Domain\Service\Email\SettingsMailerInterface;
use Nurschool\Shared\Infrastructure\Email\SendGrid\EventDispatcher\SendGridEventDispatcherInterface;
use Nurschool\Shared\Infrastructure\Email\SendGrid\Exception\SendGridException;
use Nurschool\Shared\Infrastructure\Email\SendGrid\Provider\SendGridProvider;
use Nurschool\Shared\Infrastructure\Symfony\Event\SendGridEvent;
use Nurschool\User\Domain\User;
use SendGrid\Mail\EmailAddress;
use SendGrid\Mail\Mail;
use SendGrid\Mail\MailSettings;
use SendGrid\Mail\Personalization;
use SendGrid\Mail\SandBoxMode;
use SendGrid\Mail\To;
use SendGrid\Response;

/**
 * Class SendGridMailer
 * @package Nurschool\Shared\Infrastructure\Email\SendGrid
 *
 * Sends Nurschool emails using SendGrid REST API
 */
class SendGridMailer implements MailerInterface
{
    /** @var SendGridProvider */
    private $provider;

    /** @var SettingsMailerInterface */
    private $settingsMailer;

    /**
     * @param SendGridProvider $provider
     * @param SettingsMailerInterface $settingsMailer
     */
    public function __construct(
        SendGridProvider $provider,
        SettingsMailerInterface $settingsMailer
    ) {
        $this->provider = $provider;
        $this->settingsMailer = $settingsMailer;
    }

    public function sendConfirmationEmail(User $user, string $signedUrl, \DateTimeInterface $expiresAt)
    {
        $templateId = $this->getEmailSettingValue('template', 'email_confirmation');
        $subject = $this->getEmailSettingValue('subject', 'email_confirmation');
        $address = $this->getEmailSettingValue('address', 'email_confirmation');
        $name = $this->getEmailSettingValue('address', 'email_confirmation');
        $data = [
            'url' => $signedUrl,
            'expiresAt' => $expiresAt->format('g')
        ];
        $email = $this->createMessage(
            [$address, $name],
            $user->email()->toString(),
            $subject,
            $templateId,
            $data
        );

        return $this->sendMessage($email);
    }

    public function sendResettingPasswordEmail(User $user)
    {
        // TODO: Implement sendResettingPasswordEmail() method.
    }


    protected function getEmailSettingValue(string $name, ?string $prefix = null)
    {
        $name = null !== $prefix ? "$prefix.$name" : $name;

        return $this->settingsMailer->getSetting($name);
    }

//    public function sendConfirmationEmail(UserInterface $user, VerifyEmailSignatureComponents $signatureComponents)
//    {
//        $templateId = $this->getTemplateId('confirmation');
//        $from = $this->getFrom('admin');
//        $email = $this->createMessage($from, $user->getEmail(), ' ', $templateId);
//        $email->addDynamicTemplateData('url', $signatureComponents->getSignedUrl());
//        $email->addDynamicTemplateData('expiresAt', $signatureComponents->getExpiresAt()->format('g'));
//
//        return $this->sendMessage($email);
//    }
//
//    public function sendResettingPasswordEmail(UserInterface $user, ResetPasswordToken $resetToken, int $tokenLifetime)
//    {
//        $templateId = $this->getTemplateId('resetting');
//        $from = $this->getFrom('admin');
//        $url = $this->urlGenerator->generate(
//            'reset_password',
//            ['token' => $resetToken->getToken()],
//            UrlGeneratorInterface::ABSOLUTE_URL
//        );
//        $email = $this->createMessage($from, $user->getEmail(), ' ', $templateId);
//        $email->addDynamicTemplateData('url', $url);
//        $email->addDynamicTemplateData('tokenLifetime', date('g', $tokenLifetime));
//
//        return $this->sendMessage($email);
//    }
//
//    public function sendInvitationEmail(Invitation $invitation, TokenComponents $tokenComponents)
//    {
//        $templateId = $this->getTemplateId('invitation');
//        $from = $this->getFrom('admin');
//        $url = $this->urlGenerator->generate(
//            'invitation',
//            ['token' => $tokenComponents->getPublicToken()],
//            UrlGeneratorInterface::ABSOLUTE_URL
//        );
//        $email = $this->createMessage($from, $invitation->getEmail(), ' ', $templateId);
//        $email->addDynamicTemplateData('firstname', $invitation->getFirstname());
//        $email->addDynamicTemplateData('url', $url);
//
//        return $this->sendMessage($email);
//    }
//
//
}