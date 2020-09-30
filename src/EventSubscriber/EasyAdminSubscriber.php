<?php

namespace Nurschool\EventSubscriber;


use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Nurschool\Entity\School;
use Nurschool\Event\RegisteredUserEvent;
use Nurschool\Model\UserInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    /** @var Security */
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setAdminSchool'],
            AfterEntityPersistedEvent::class => ['sendConfirmationEmail'],
        ];
    }

    public function setAdminSchool(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!$entity instanceof School) {
            return;
        }

        $entity->addAdmin($this->security->getUser());
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