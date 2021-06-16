<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Nurschool\Shared\Infrastructure\Persistence\Doctrine\EventSubscriber;


use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use Nurschool\Shared\Domain\AggregateRoot;
use Nurschool\Shared\Domain\Event\DomainEventDispatcher;

class PublishDomainEventSubscriber implements EventSubscriberInterface
{
    /** @var array */
    private $events = [];

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
            Events::postRemove,
            Events::postFlush
        ];
    }

    public function postPersist(LifecycleEventArgs $event)
    {
        $this->collectEvent($event);
    }

    public function postFlush(PostFlushEventArgs $event)
    {
        $this->dispatchCollectedEvents();
    }

    private function collectEvent(LifecycleEventArgs $event): void
    {
        $entity = $event->getEntity();
        if ($entity instanceof AggregateRoot) {
            $events = $entity->pullDomainEvents();
            foreach($events as $event) {
                // We index by object hash, not to have the same event twice
                $this->events[spl_object_hash($event)] = $event;
            }
//            $this->publishEvents($events);
        }
    }

    private function dispatchCollectedEvents(): void
    {
        $events = $this->events;
        $this->events = [];

        foreach($events as $event) {
            $this->domainEventDispatcher->dispatch($event);
        }

        // Maybe listeners emitted some new events!
        if (!empty($this->events)) {
            $this->dispatchCollectedEvents();
        }
    }

}