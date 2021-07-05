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
use Nurschool\Shared\Infrastructure\Symfony\Event\UserCreated;
use Nurschool\User\Domain\ValueObject\Credentials;
use Nurschool\User\Domain\ValueObject\Email;
use Nurschool\User\Domain\ValueObject\FullName;
use Nurschool\User\Domain\ValueObject\GoogleId;
use Nurschool\User\Domain\ValueObject\HashedPassword;

final class CreateUserCommandHandler implements CommandHandler
{
    /** @var UserCreator */
    private $creator;

    public function __construct(UserCreator $creator)
    {
        $this->creator = $creator;
    }

    public function __invoke(CreateUserCommand $command): void
    {
        $email = Email::fromString($command->email);
        if (null === $command->googleId) {
            $hashedPassword = HashedPassword::encode($command->plainPassword);
            $this->creator->createUser($email, $hashedPassword);
        } else {
            $googleId = GoogleId::fromString($command->googleId);
            $fullName = null !== $command->firstname && null !== $command->lastname ?
                FullName::create($command->firstname, $command->lastname) : null;
            $this->creator->createGoogleUser($email, $googleId, $fullName);
        }
    }
}