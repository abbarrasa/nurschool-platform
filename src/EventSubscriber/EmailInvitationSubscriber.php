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


use Doctrine\ORM\EntityManagerInterface;
use Nurschool\Event\InvitedUserEvent;
use Nurschool\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EmailInvitationSubscriber implements EventSubscriberInterface
{
    protected $mailer;

    protected $entityManager;

    public function __construct(MailerInterface $mailer, EntityManagerInterface $entityManager)
    {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            InvitedUserEvent::NAME => 'sendInvitationEmail'
        ];
    }

    public function sendInvitationEmail(InvitedUserEvent $event)
    {
        $invitation = $event->getInvitation();

        $this->mailer->sendInvitationEmail($invitation);

        $invitation->setSent(true);
        $this->entityManager->persist($invitation);
        $this->entityManager->flush();
    }
}