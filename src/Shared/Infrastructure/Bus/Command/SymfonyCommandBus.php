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

namespace Nurschool\Shared\Infrastructure\Bus\Command;


/***
 *   * You're ready to use the Messenger component. You can define your own message buses
or start using the default one right now by injecting the message_bus service
or type-hinting Symfony\Component\Messenger\MessageBusInterface in your code.

 * To send messages to a transport and handle them asynchronously:

1. Uncomment the MESSENGER_TRANSPORT_DSN env var in .env
and framework.messenger.transports.async in config/packages/messenger.yaml;
2. Route your message classes to the async transport in config/packages/messenger.yaml.

 */

use Nurschool\Shared\Application\Command\Command;
use Nurschool\Shared\Application\Command\CommandBus;
use Nurschool\Shared\Infrastructure\Symfony\Bus\MessageBusExceptionTrait;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyCommandBus implements CommandBus
{
    use MessageBusExceptionTrait;

    private $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function dispatch(Command $command): void
    {
        try {
            $this->messageBus->dispatch($command);
        } catch(NoHandlerForMessageException $exception) {
            throw new EventNotRegisteredException($command);
        } catch(HandlerFailedException $exception) {
            $this->throwException($exception);
        }
    }
}