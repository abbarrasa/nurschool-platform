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

namespace Nurschool\User\Domain\Model\Repository;


use Nurschool\Shared\Domain\Model\Repository\RepositoryInterface;
use Nurschool\User\Domain\User;
use Nurschool\User\Domain\ValueObject\Email;

interface UserRepository extends RepositoryInterface
{
    public function findByEmail(Email $email): User;

    public function updateLastLogin(User $user);

}