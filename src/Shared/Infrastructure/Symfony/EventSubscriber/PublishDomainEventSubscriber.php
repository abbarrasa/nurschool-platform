<?php


namespace Nurschool\Shared\Infrastructure\Symfony\EventSubscriber;


use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Nurschool\Shared\Domain\AggregateRoot;
use Nurschool\Shared\Domain\Event\DomainEvent;
use Nurschool\Shared\Domain\Event\DomainEventDispatcher;

class PublishDomainEventSubscriber implements EventSubscriberInterface
{
    /** @var DomainEventDispatcher */
    private $domainEventDispatcher;

    public function __construct(DomainEventDispatcher $domainEventDispatcher)
    {
        $this->domainEventDispatcher = $domainEventDispatcher;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove
        ];
    }

    public function postPersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if ($entity instanceof AggregateRoot) {
            $events = $entity->pullDomainEvents();
            $this->publishEvents($events);
        }
    }

    private function publishEvents(array $events)
    {
        /** @var DomainEvent $event */
        foreach($events as $event) {
            $this->domainEventDispatcher->dispatch($event);
        }
    }

}