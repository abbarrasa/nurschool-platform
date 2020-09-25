<?php

namespace Nurschool\EventSubscriber;


use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use Nurschool\Event\RegisteredUserEvent;
use Nurschool\Model\UserInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            AfterEntityPersistedEvent::class => ['sendConfirmationEmail'],
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
}