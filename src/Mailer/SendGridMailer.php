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
use OG\SendGridBundle\Provider\SendGridProvider;
use SendGrid\Mail\Mail;
use SendGrid\Mail\MailSettings;
use SendGrid\Mail\SandBoxMode;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SendGridMailer implements MailerInterface
{
    /** @var SendGridProvider */
    protected $provider;

    /** @var TranslatorInterface */
    protected $translator;

    /** @var array */
    protected $config;

    /**
     * SendGridMailer constructor.
     * @param SendGridProvider $provider
     * @param TranslatorInterface $translator
     * @param array $config
     */
    public function __construct(
        SendGridProvider $provider,
        TranslatorInterface $translator,
        array $config
    ) {
        $this->provider = $provider;
        $this->translator = $translator;
        $this->config = $config;
    }

    public function sendConfirmationEmail(UserInterface $user, string $signedUrl, \DateTimeInterface $expiresAt)
    {
        $templateId = $this->getTemplateId('confirmation');
//        $subject    = $this->translator->trans('email.subject.confirmation', [], 'Nurschool');
        $subject = 'Please Confirm your Email';
        $from       = $this->getFrom('admin');
        $email      = $this->createMessage($from, $user->getEmail(), $subject, $templateId);
        $email->addDynamicTemplateData('url', $signedUrl);
        $email->addDynamicTemplateData('expiresAt', $expiresAt->format('g'));

        return $this->sendMessage($email);
    }

    /**
     * Creates an email with SendGrid API
     * @param null $from
     * @param null $to
     * @param null $subject
     * @param null $templateId
     * @return \SendGrid\Mail\Mail
     * @throws \SendGrid\Mail\TypeException
     */
    protected function createMessage($from = null, $to = null, $subject = null, $templateId = null)
    {
        $email        = $this->provider->createMessage();
        $mailSettings = new MailSettings();
        $sandboxMode  = new SandBoxMode();
        $sandboxMode->setEnable($this->config['sandbox']);
        $mailSettings->setSandboxMode($sandboxMode);
        $email->setMailSettings($mailSettings);

        if (isset($from)) {
            if (is_array($from)) {
                list($fromEmail, $senderName) = $from;
                $email->setFrom($fromEmail, $senderName);
            } else {
                $email->setFrom($from);
            }
        }

        if (isset($to)) {
            $email->addTo($to);
        }

        if (isset($subject)) {
            $email->setSubject($subject);
        }

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