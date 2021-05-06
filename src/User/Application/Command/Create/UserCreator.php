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


use Nurschool\User\Domain\Model\Repository\UserRepositoryInterface;
use Nurschool\User\Domain\ValueObject\HashedPassword;

final class UserCreator
{
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Email $email, HashedPassword $hashedPassword)
    {
        $this->repository->save();
    }
}