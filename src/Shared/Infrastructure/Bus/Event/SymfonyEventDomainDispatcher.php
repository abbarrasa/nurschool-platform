<?php


namespace Nurschool\Shared\Infrastructure\Bus\Event;


use Nurschool\Shared\Application\Event\DomainEvent;
use Nurschool\Shared\Application\Event\DomainEventDispatcher;
use Nurschool\Shared\Application\Event\DomainEventListener;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class SymfonyEventDomainDispatcher implements DomainEventDispatcher
{
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function addListener(string $eventName, DomainEventListener $listener): void
    {
        $this->dispatcher->addListener($eventName, $listener);
    }

    public function dispatch(DomainEvent $event): void
    {
        $eventName = $event->eventName();
        $this->dispatcher->dispatch($event, $eventName);
    }

}