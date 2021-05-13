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


use Nurschool\Shared\Application\Event\DomainEventDispatcher;
use Nurschool\Shared\Domain\Model\CreatorInterface;
use Nurschool\Shared\Infrastructure\Symfony\Event\UserCreated;
use Nurschool\User\Domain\Model\Repository\UserRepositoryInterface;
use Nurschool\User\Domain\User;
use Nurschool\User\Domain\ValueObject\Credentials;
use Nurschool\User\Domain\ValueObject\Email;
use Nurschool\User\Domain\ValueObject\HashedPassword;

final class UserCreator implements CreatorInterface
{
    private $repository;
    private $eventDispatcher;

    public function __construct(UserRepositoryInterface $repository, DomainEventDispatcher $eventDispatcher)
    {
        $this->repository = $repository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(Email $email, HashedPassword $hashedPassword)
    {
        $user = User::create($email, $hashedPassword);
        $this->repository->save($user);

        $event = new UserCreated($user);
        $this->eventDispatcher->dispatch($event);
    }
}