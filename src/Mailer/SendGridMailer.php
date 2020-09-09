<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Mailer;


use Nurschool\Mailer\Exception\ConfigMailerException;
use Nurschool\Model\UserInterface;
use OG\SendGridBundle\Provider\SendGridProvider;
use SendGrid\Mail\Mail;
use SendGrid\Mail\MailSettings;
use SendGrid\Mail\SandBoxMode;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;
use SymfonyCasts\Bundle\VerifyEmail\Model\VerifyEmailSignatureComponents;

class SendGridMailer implements MailerInterface
{
    /** @var SendGridProvider */
    protected $provider;

    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    /** @var array */
    protected $config;

    /**
     * SendGridMailer constructor.
     * @param SendGridProvider $provider
     * @param UrlGeneratorInterface $urlGenerator
     * @param array $config
     */
    public function __construct(
        SendGridProvider $provider,
        UrlGeneratorInterface $urlGenerator,
        array $config
    ) {
        $this->provider = $provider;
        $this->urlGenerator = $urlGenerator;
        $this->config = $config;
    }

    public function sendConfirmationEmail(UserInterface $user, VerifyEmailSignatureComponents $signatureComponents)
    {
        $templateId = $this->getTemplateId('confirmation');
        $from = $this->getFrom('admin');
        $email = $this->createMessage($from, $user->getEmail(), ' ', $templateId);
        $email->addDynamicTemplateData('url', $signatureComponents->getSignedUrl());
        $email->addDynamicTemplateData('expiresAt', $signatureComponents->getExpiresAt()->format('g'));

        return $this->sendMessage($email);
    }

    public function sendResettingPasswordEmail(UserInterface $user, ResetPasswordToken $resetToken, int $tokenLifetime)
    {
        $templateId = $this->getTemplateId('resetting');
        $from = $this->getFrom('admin');
        $url = $this->urlGenerator->generate('reset_password', ['token' => $resetToken->getToken()], UrlGeneratorInterface::ABSOLUTE_URL);
        $email = $this->createMessage($from, $user->getEmail(), ' ', $templateId);
        $email->addDynamicTemplateData('url', $url);
        $email->addDynamicTemplateData('tokenLifetime', date('g', $tokenLifetime));

        return $this->sendMessage($email);
    }

    /**
     * Creates an email with SendGrid API
     * @param $from
     * @param $to
     * @param string $subject
     * @param null $templateId
     * @return Mail
     * @throws \SendGrid\Mail\TypeException
     */
    protected function createMessage($from, $to, $subject = ' ', $templateId = null)
    {
        $email        = $this->provider->createMessage();
        $mailSettings = new MailSettings();
        $sandboxMode  = new SandBoxMode();
        $sandboxMode->setEnable($this->config['sandbox']);
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

        if (isset($templateId)) {
            $email->setTemplateId($templateId);
        }

        return $email;
    }

    /**
     * Sends an email with SendGrid API
     * @param Mail $email
     * @return string|null
     * @throws \OG\SendGridBundle\Exception\SendGridException
     */
    protected function sendMessage(Mail $email)
    {
        return $this->provider->send($email);
    }

    /**
     * Gets address and sender name for from of a email
     * @param $key
     * @return array|mixed
     */
    private function getFrom($key)
    {
        if (!isset($this->config['default']['address'])) {
            throw new ConfigMailerException('Not found default address.');
        }

        $address = isset($this->config[$key]['address']) ? $this->config[$key]['address'] : $this->config['default']['address'];
        if (isset($this->config[$key]['name'])) {
            return [$address, $this->config[$key]['name']];
        } else {
            if (isset($this->config['default']['name'])) {
                return [$address, $this->config['default']['name']];
            }
        }

        return $address;
    }

    /**
     * Gets template ID for a email
     * @param $key
     * @return mixed
     */
    protected function getTemplateId($key)
    {
        if (isset($this->config[$key]['template'])) {
            return $this->config[$key]['template'];
        }

        throw new ConfigMailerException(sprintf('Not found template id for "%s" email.', $key));
    }
}