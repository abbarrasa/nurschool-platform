<?php

namespace Nurschool\EventSubscriber;


use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Nurschool\Entity\Invitation;
use Nurschool\Entity\School;
use Nurschool\Event\InvitedUserEvent;
use Nurschool\Event\RegisteredUserEvent;
use Nurschool\Model\UserInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            AfterEntityPersistedEvent::class => ['sendConfirmationEmail', 'sendInvitationEmail'],
        ];
    }

    public function sendConfirmationEmail(AfterEntityPersistedEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $entity = $event->getEntityInstance();

        if (!$entity instanceof UserInterface) {
            return;
        }

        // Propagate the event to send the confirmation email
        $dispatcher->dispatch(new RegisteredUserEvent($entity), RegisteredUserEvent::NAME);
    }

    public function sendInvitationEmail(AfterEntityPersistedEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $entity = $event->getEntityInstance();

        if (!$entity instanceof Invitation) {
            return;
        }

        // Propagate the event to send the confirmation email
        $dispatcher->dispatch(new InvitedUserEvent($entity), InvitedUserEvent::NAME);
    }
}