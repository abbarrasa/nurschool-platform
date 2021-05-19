<?php


namespace Nurschool\Shared\Infrastructure\Bus\Event;


use Nurschool\Shared\Domain\Event\DomainEvent;
use Nurschool\Shared\Domain\Event\DomainEventDispatcher;
use Nurschool\Shared\Domain\Event\DomainEventListener;
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