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



use FOS\UserBundle\Util\TokenGeneratorInterface;
use Nurschool\Event\Oauth2UserRegisteredEvent;
use Nurschool\Mailer\MailerInterface;
use Nurschool\Model\UserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class EmailConfirmationSubscriber implements EventSubscriberInterface
{
    /** @var MailerInterface  */
    protected $mailer;
    /** @var TokenGeneratorInterface  */
    protected $tokenGenerator;
    /** @var SessionInterface  */
    protected $session;

    public function __construct(MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator, SessionInterface $session)
    {
        $this->mailer = $mailer;
        $this->tokenGenerator = $tokenGenerator;
        $this->session = $session;
    }

    public static function getSubscribedEvents()
    {
        return [
            Oauth2UserRegisteredEvent::NAME => 'onRegistration'
        ];
    }

    public function onRegistration(Oauth2UserRegisteredEvent $event)
    {
        /** @var UserInterface $user */
        $user = $event->getUser();

        $user->setEnabled(false);
        if (null === $user->getConfirmationToken()) {
            $user->setConfirmationToken($this->tokenGenerator->generateToken());
        }

        $this->mailer->sendConfirmationEmailMessage($user);

        $this->session->set('fos_user_send_confirmation_email/email', $user->getEmail());
    }
}