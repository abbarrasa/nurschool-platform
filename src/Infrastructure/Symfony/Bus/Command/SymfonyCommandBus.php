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

namespace Nurschool\Platform\Infrastructure\Symfony\Bus\Command;

use Nurschool\Common\Application\Command\Command;
use Nurschool\Common\Application\Command\CommandBus;
use Nurschool\Common\Application\Command\Exception\CommandNotRegistered;
use Nurschool\Platform\Infrastructure\Symfony\Bus\SymfonyMessageBus;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyCommandBus extends SymfonyMessageBus implements CommandBus
{
  public function __construct(MessageBusInterface $commandBus)
  {
      parent::__construct($commandBus);
  }

    public function dispatch(Command $command): void
    {
        try {
            $this->messageBus->dispatch($command);
        } catch (NoHandlerForMessageException $exception) {
            throw new CommandNotRegistered($command);
        } catch (HandlerFailedException $exception) {
            $this->throwException($exception);
        }
    }
}
