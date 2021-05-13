<?php


namespace Nurschool\Shared\Infrastructure\Bus\Event;


use Nurschool\Shared\Application\Event\DomainEvent;
use Nurschool\Shared\Application\Event\DomainEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class SymfonyEventDomainDispatcher implements DomainEventDispatcher
{
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function dispatch(DomainEvent $event): void
    {
        $eventName = $event->eventName();
        $this->dispatcher->dispatch($event, $eventName);
    }

}