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

namespace Nurschool\Platform\Infrastructure\Symfony\Bus\Event;

use Nurschool\Common\Domain\Event\DomainEvent;
use Nurschool\Common\Domain\Event\DomainEventDispatcher;
use Nurschool\Common\Domain\Event\Exception\EventNotRegisteredException;
use Nurschool\Platform\Infrastructure\Symfony\Bus\SymfonyMessageBus;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;

final class RabbitMqDomainEventDispatcher extends SymfonyMessageBus implements DomainEventDispatcher
{
    public function __construct(MessageBusInterface $eventBus)
    {
        parent::__construct($eventBus);
    }

    public function dispatch(DomainEvent $event): void
    {
        try {
            $this->messageBus->dispatch($event, [
                new AmqpStamp($event::eventName())
            ]);
        } catch(NoHandlerForMessageException $exception) {
            throw new EventNotRegisteredException($event);
        } catch(HandlerFailedException $exception) {
            $this->throwException($exception);
        }
    }
}