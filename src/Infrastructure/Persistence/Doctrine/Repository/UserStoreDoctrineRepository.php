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

namespace Nurschool\Platform\Infrastructure\Persistence\Doctrine\Repository;

use Nurschool\Common\Infrastructure\Persistence\Doctrine\Repository\StoreDoctrineRepository;
use Nurschool\Platform\Domain\Repository\UserStoreRepository;
use Nurschool\Platform\Domain\User;

final class UserStoreDoctrineRepository extends StoreDoctrineRepository implements UserStoreRepository
{
    public function entityClass(): string
    {
        return User::class;
    }
}
