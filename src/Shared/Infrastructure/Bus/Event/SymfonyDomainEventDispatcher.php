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

namespace Nurschool\Shared\Infrastructure\Bus\Event;


use Nurschool\Shared\Domain\Event\DomainEvent;
use Nurschool\Shared\Domain\Event\DomainEventDispatcher;
use Nurschool\Shared\Infrastructure\Symfony\Bus\MessageBusExceptionTrait;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyDomainEventDispatcher implements DomainEventDispatcher
{
    use MessageBusExceptionTrait;

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