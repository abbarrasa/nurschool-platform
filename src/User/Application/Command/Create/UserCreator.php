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
use Nurschool\User\Domain\Model\Repository\UserRepository;
use Nurschool\User\Domain\User;
use Nurschool\User\Domain\ValueObject\Email;
use Nurschool\User\Domain\ValueObject\FullName;
use Nurschool\User\Domain\ValueObject\GoogleId;
use Nurschool\User\Domain\ValueObject\HashedPassword;

final class UserCreator
{
    /** @var UserRepository */
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createUser(Email $email, HashedPassword $hashedPassword): User
    {
        $user = User::create($email, $hashedPassword);
        $this->save($user);

        return $user;
    }

    public function createGoogleUser(Email $email, GoogleId $googleId, ?FullName $fullName = null): User
    {
        $user = User::create($email);
        $user->setGoogleId($googleId);

        $user = User::createFromGoogleId($email, $googleId);

        $this->save($user);

        return $user;
    }

    private function save(User $user)
    {
        $this->repository->save($user);
    }
}