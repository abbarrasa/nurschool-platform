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
use Nurschool\User\Domain\ValueObject\HashedPassword;

final class UserCreator
{
    /** @var UserRepository */
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(Email $email, HashedPassword $hashedPassword): User
    {
        $user = User::create($email, $hashedPassword);
        $this->repository->save($user);

        return $user;
    }
}