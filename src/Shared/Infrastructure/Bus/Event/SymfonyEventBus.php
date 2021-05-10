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


use Nurschool\Shared\Application\Event\DomainEventInterface;
use Nurschool\Shared\Application\Event\EventBusInterface;
use Nurschool\Shared\Infrastructure\Symfony\Bus\MessageBusExceptionTrait;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyEventBus implements EventBusInterface
{
    use MessageBusExceptionTrait;

    private $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function publish(DomainEventInterface ...$events): void
    {
        try {
            foreach($events as $event) {
                $this->messageBus->dispatch($event);
            }
        } catch(NoHandlerForMessageException $exception) {
            throw new EventNotRegisteredException($event);
        } catch(HandlerFailedException $exception) {
            $this->throwException($exception);
        }

    }

}