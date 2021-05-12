<?php


namespace Nurschool\Shared\Infrastructure\Bus\Event;


use Nurschool\Shared\Application\Event\DomainEvent;
use Nurschool\Shared\Application\Event\DomainEventSubscriber;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class SymfonyEventDomainSubscriber implements DomainEventSubscriber
{
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function dispatch(DomainEvent $event): void
    {
        // TODO: Implement dispatch() method.
    }

}