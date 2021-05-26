<?php


namespace Nurschool\Shared\Infrastructure\Bus\Event;


use Nurschool\Shared\Domain\Event\DomainEvent;
use Nurschool\Shared\Domain\Event\DomainEventDispatcher;
use Nurschool\Shared\Infrastructure\Bus\Command\EventNotRegisteredException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyDomainEventDispatcher implements DomainEventDispatcher
{
    private $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function dispatch(DomainEvent $event): void
    {
        try {
            $this->messageBus->dispatch($event);
        } catch(NoHandlerForMessageException $exception) {
            throw new EventNotRegisteredException($event);
        } catch(HandlerFailedException $exception) {
            $this->throwException($exception);
        }
    }

}