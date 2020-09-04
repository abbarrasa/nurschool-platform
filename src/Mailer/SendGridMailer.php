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


use FOS\UserBundle\Model\UserInterface;
use Nurschool\Mailer\Exception\ConfigMailerException;
use OG\SendGridBundle\Provider\SendGridProvider;
use SendGrid\Mail\MailSettings;
use SendGrid\Mail\SandBoxMode;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SendGridMailer implements MailerInterface
{
    /** @var SendGridProvider */
    protected $provider;

    /** @var UrlGeneratorInterface */
    protected $router;

    /** @var TranslatorInterface */
    protected $translator;

    protected $config;

    /**
     * SendGridMailer constructor.
     * @param SendGridProvider $provider
     * @param UrlGeneratorInterface $router
     * @param TranslatorInterface $translator
     * @param array config
     */
    public function __construct(
        SendGridProvider $provider,
        UrlGeneratorInterface $router,
        TranslatorInterface $translator,
        array $config
    ) {
        $this->provider = $provider;
        $this->router = $router;
        $this->translator = $translator;
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        $templateId = $this->getTemplateId('confirmation');
        $subject    = $this->translator->trans('email.subject.confirmation', [], 'Nurschool');
        $from       = $this->getFrom('admin');
        $url        = $this->router->generate('fos_user_registration_confirm', ['token' => $user->getConfirmationToken()], UrlGeneratorInterface::ABSOLUTE_URL);
        $email      = $this->createMessage($from, $user->getEmail(), $subject, $templateId);
        $email->addDynamicTemplateDatas([
            '%url%', $url,
            '%name%', $user->getUsername()]
        );

        return $this->sendMessage($email);
    }

    /**
     * @inheritDoc
     */
    public function sendResettingEmailMessage(UserInterface $user)
    {
        if (!$user instanceof \Nurschool\Model\UserInterface) {
            throw new \InvalidArgumentException(sprintf('SendResettingEmail method expected entity of type %s', \Nurschool\Model\UserInterface::class));
        }

        $from       = $this->getFrom('admin');
        $templateId = $this->getTemplateId('resetting');
        $subject    = $this->translator->trans('email.subject.resetting', [], 'Nurschool');
        $url        = $this->router->generate('sonata_user_admin_resetting_reset', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);
        $email      = $this->createMessage($from, $user->getEmail(), $subject, $templateId);
        $email->addDynamicTemplateDatas([
            '%name%' => $user->getFirstname(),
            '%url%' => $url
        ]);

        return $this->sendMessage($email);    }

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
     * @param $sender
     * @return array
     */
    private function getFrom($sender)
    {
        $config = $this->config['sender'];
        if (isset($config[$sender])) {
            $address    = $config[$sender]['address'];
            $senderName = $config[$sender]['name'];
        } else {
            $address    = $config['default']['address'];
            $senderName = $config['default']['name'];
        }

        return [$address, $senderName];
    }

    /**
     * Gets template ID for a email
     * @param $name
     * @return mixed
     */
    protected function getTemplateId($name)
    {
        if (isset($this->config['template'][$name])) {
            return $this->config['template'][$name];
        }

        throw new ConfigMailerException(sprintf('Not found template id for "%s" email.', $name));
    }
}