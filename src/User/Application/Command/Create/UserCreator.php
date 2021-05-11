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


use Nurschool\Shared\Application\Event\EventBusInterface;
use Nurschool\Shared\Domain\Model\CreatorInterface;
use Nurschool\User\Domain\Model\Event\UserCreated;
use Nurschool\User\Domain\Model\Repository\UserRepositoryInterface;
use Nurschool\User\Domain\ValueObject\Credentials;
use Nurschool\User\Domain\ValueObject\Email;
use Nurschool\User\Domain\ValueObject\HashedPassword;

final class UserCreator implements CreatorInterface
{
    private $repository;
    private $eventBus;

    public function __construct(UserRepositoryInterface $repository, EventBusInterface $eventBus)
    {
        $this->repository = $repository;
        $this->eventBus = $eventBus;
    }

    public function __invoke(Email $email, HashedPassword $hashedPassword)
    {
        $user = $this->repository->create($credentials);
        $this->repository->save($user);

        $event = new UserCreated($user->getId());
        $this->eventBus->publish($event);
    }
}