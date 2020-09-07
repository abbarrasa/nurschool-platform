<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\EventSubscriber;


use Nurschool\Event\RegisteredUserEvent;
use Nurschool\Mailer\MailerInterface;
use Nurschool\Security\EmailVerifier;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class EmailConfirmationSubscriber implements EventSubscriberInterface
{
    /** @var EmailVerifier  */
    protected $emailVerifier;

    /** @var MailerInterface  */
    protected $mailer;

    /** @var SessionInterface  */
    protected $session;

    public function __construct(EmailVerifier $emailVerifier, MailerInterface $mailer, SessionInterface $session)
    {
        $this->emailVerifier = $emailVerifier;
        $this->mailer = $mailer;
        $this->session = $session;
    }

    public static function getSubscribedEvents()
    {
        return [
            RegisteredUserEvent::NAME => 'onRegistration'
        ];
    }

    public function onRegistration(RegisteredUserEvent $event)
    {
        $user = $event->getUser();

        // generate a signed url and email it to the user
        $signatureComponents = $this->emailVerifier->generateSignatureConfirmation('verify_email', $user);
        $this->mailer->sendConfirmationEmail($user, $signatureComponents);

        $this->session->set('nurschool_send_confirmation_email/email', $user->getEmail());
        $this->session->set('nurschool_send_confirmation_email/expiresAt', $signatureComponents->getExpiresAt());
    }
}