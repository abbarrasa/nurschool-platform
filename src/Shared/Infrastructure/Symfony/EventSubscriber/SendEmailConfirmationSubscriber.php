<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Shared\Infrastructure\Symfony\EventSubscriber;


use Nurschool\Shared\Infrastructure\Symfony\Event\UserCreated;
use Nurschool\Shared\Infrastructure\Symfony\Security\EmailVerifier;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SendEmailConfirmationSubscriber implements EventSubscriberInterface
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserCreated::NAME => 'sendEmail'
        ];
    }

    public function sendEmail(UserCreated $event)
    {
        $user = $event->getUser();
        $this->emailVerifier->sendSignedUrl($user);
    }
}