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
use Nurschool\Shared\Infrastructure\Email\SendGrid\Exception\SendGridException;
use Nurschool\Shared\Infrastructure\Symfony\Event\SendGridEvent;
use Nurschool\User\Domain\User;
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
    /** @var SettingsMailerInterface */
    private $settingsMailer;

    /** @var SendGridEventDispatcherInterface */
    private $eventDispatcher;

    /** @var \SendGrid */
    private $provider;

    /** @var mixed */
    private $redirectTo;

    /** @var Personalization[] */
    private $originalPersonalization;

    /** @var \ReflectionProperty */
    private $personalizationReflection;

    /** @var Personalization */
    private $redirectPersonalization;

    /**
     * SendGridMailer constructor.
     * @param \SendGrid $provider
     * @param SettingsMailerInterface $settingsMailer
     * @param SendGridEventDispatcherInterface $eventDispatcher
     * @throws \SendGrid\Mail\TypeException
     */
    public function __construct(
        \SendGrid $provider,
        SettingsMailerInterface $settingsMailer,
        SendGridEventDispatcherInterface $eventDispatcher)
    {
        $this->provider = $provider;
        $this->settingsMailer = $settingsMailer;
        $this->eventDispatcher = $eventDispatcher;

        if(false !== ($this->redirectTo = $this->settingsMailer->getSetting('redirect_to'))) {
            $redirectToAddress = $this->settingsMailer->getSetting('redirect_to_address');
            $this->redirectPersonalization = new Personalization();
            $this->redirectPersonalization->addTo(new To($redirectToAddress));

            $this->personalizationReflection = new \ReflectionProperty(Mail::class, 'personalization');
            $this->personalizationReflection->setAccessible(true);
        }
    }

    public function sendConfirmationEmail(User $user, string $signedUrl, \DateTimeInterface $expiresAt)
    {
        $templateId = $this->settingsMailer->getSetting('confirmation.template');
        $subject = $this->settingsMailer->getSetting('confirmation.subject');
        $from = [
            $this->settingsMailer->getSetting('confirmation.address'),
            $this->settingsMailer->getSetting('confirmation.name')
        ];
        $email = $this->createMessage($from, $user->email()->toString(), $subject, $templateId);
        $email->addDynamicTemplateData('url', $signedUrl);
        $email->addDynamicTemplateData('expiresAt', $expiresAt->format('g'));

        return $this->sendMessage($email);
    }

    public function sendResettingPasswordEmail(User $user)
    {
        // TODO: Implement sendResettingPasswordEmail() method.
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
    /**
     * Creates an email with SendGrid REST API
     * @param $from
     * @param $to
     * @param $subject
     * @param null $templateId
     * @return Mail
     * @throws \SendGrid\Mail\TypeException
     */
    protected function createMessage($from, $to, $subject, $templateId = null): Mail
    {
        $email        = new Mail();
        $mailSettings = new MailSettings();
        $sandboxMode  = new SandBoxMode();
        $sandboxMode->setEnable($this->settingsMailer->getSetting('sandbox'));
        $mailSettings->setSandboxMode($sandboxMode);
        $email->setMailSettings($mailSettings);

        if (is_array($from)) {
            list($fromEmail, $senderName) = $from;
            $email->setFrom($fromEmail, $senderName);
        } else {
            $email->setFrom($from);
        }

        $email->addTo($to);
        $email->setSubject($subject);

        if (!empty($templateId)) {
            $email->setTemplateId($templateId);
        }

        return $email;
    }

    /**
     * Sends an email with SendGrid REST API
     * @param Mail $mail
     * @return string|null
     * @throws SendGridException
     */
    protected function sendMessage(Mail $mail): ?string
    {
        $this->eventDispatcher->dispatch(new SendGridEvent($mail), SendGridEvent::STARTED);
        if ($this->disableDelivery) {
            $this->eventDispatcher->dispatch(new SendGridEvent($mail), SendGridEvent::FINISHED);
            return null;
        }

        try {
            $this->redirect($mail);

            $response = $this->provider->send($mail);

            $this->reverseRedirection($mail);
            $this->checkResponse($response);

            $messageId = $this->getMessageId($response);

            $this->eventDispatcher->dispatch(new SendGridEvent($mail, $messageId), SendGridEvent::FINISHED);

            return $messageId;

        } catch (\Exception $exception) {
            $this->reverseRedirection($mail);
            $this->eventDispatcher->dispatch(new SendGridEvent($mail), SendGridEvent::FAILED);
            if($exception instanceof SendGridException) {
                throw $exception;
            }

            throw new SendGridException($exception->getMessage());
        }
    }

    private function redirect(Mail $mail): void
    {
        if($this->redirectTo !== false) {
            $this->originalPersonalization = $mail->getPersonalizations();

            $this->personalizationReflection->setValue($mail, [$this->redirectPersonalization]);
        }
    }

    private function reverseRedirection(Mail $mail): void
    {
        if($this->redirectTo !== false) {
            $this->personalizationReflection->setValue($mail, $this->originalPersonalization);
        }
    }

    private function getMessageId(Response $response): ?string
    {
        try {
            return $response->headers(true)['X-Message-Id'];
        } catch (\Exception $e) {
            throw new SendGridException('X-Message-Id header not found in SendGrid API response');
        }
    }

    private function checkResponse(Response $response): void
    {
        if ($response->statusCode() == 401) {
            throw new UnauthorizedSendGridException($response->body());
        }

        if ($response->statusCode() == 403) {
            throw new AccessDeniedSendGridException($response->body());
        }

        if (preg_match('/5[0-9]{2}/', strval($response->statusCode()))) {
            throw new SendGridException($response->body());
        }

        if (preg_match('/4[0-9]{2}/', strval($response->statusCode()))) {
            throw new BadRequestSendGridException($response->body());
        }
    }
}