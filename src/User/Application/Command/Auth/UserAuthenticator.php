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

namespace Nurschool\User\Application\Command\Auth;


use Nurschool\Shared\Domain\Event\DomainEventDispatcher;
use Nurschool\Shared\Infrastructure\Symfony\Event\UserAuthenticated;
use Nurschool\User\Domain\Model\Repository\UserRepositoryInterface;
use Nurschool\User\Domain\User;
use Nurschool\User\Domain\ValueObject\Auth\Credentials;

class UserAuthenticator
{
    private $repository;
    private $eventDispatcher;

    public function __construct(UserRepositoryInterface $repository, DomainEventDispatcher $eventDispatcher)
    {
        $this->repository = $repository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function authenticate(Credentials $credentials)
    {
        $user = $this->repository->findByEmail($credentials->email);
        $this->ensureCredentialsAreValid($user, $credentials->plainPassword);

        $event = UserAuthenticated::create($user);
        $this->eventDispatcher->dispatch($event);
    }

    private function ensureCredentialsAreValid(User $user, string $plainPassword): void
    {
        if (null === $user ||
            !$user->password()->match($plainPassword)
        ) {
            throw new BadCredentials();
        }
    }
}