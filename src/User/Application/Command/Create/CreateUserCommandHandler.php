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

namespace Nurschool\User\Application\Command\Create;

use Nurschool\Shared\Application\Command\CommandHandler;
use Nurschool\Shared\Domain\Event\DomainEventDispatcher;
use Nurschool\Shared\Infrastructure\Symfony\Event\UserCreated;
use Nurschool\User\Domain\ValueObject\Credentials;

final class CreateUserCommandHandler implements CommandHandler
{
    private $creator;
    private $eventDispatcher;

    public function __construct(UserCreator $creator, DomainEventDispatcher $eventDispatcher)
    {
        $this->creator = $creator;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(CreateUserCommand $command): void
    {
        $email = $command->getEmail();
        $hashedPassword = $command->getHashedPassword();

        $user = $this->creator->create($email, $hashedPassword);

        $event = new UserCreated($user);
        $this->eventDispatcher->dispatch($event);
    }
}