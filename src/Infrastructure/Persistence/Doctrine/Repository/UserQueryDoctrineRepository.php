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

use Nurschool\Common\Domain\AggregateRoot;
use Nurschool\Common\Domain\ValueObject\Uuid;
use Nurschool\Common\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;
use Nurschool\Platform\Domain\Repository\UserQueryRepository;
use Nurschool\Platform\Domain\User;
use Nurschool\Platform\Domain\ValueObject\Email;
use Nurschool\Platform\Domain\ValueObject\GoogleId;
use Nurschool\Platform\Infrastructure\Persistence\Doctrine\Exception\UserNotFound;

final class UserQueryDoctrineRepository extends DoctrineRepository implements UserQueryRepository
{
    public function entityClass(): string
    {
        return User::class;
    }

    public function findByUuidOrFail(Uuid $uuid): User
    {
        if (null === ($user = $this->objectRepository->find((string) $uuid))) {
            throw UserNotFound::fromEmail((string) $uuid);
        }

        return $user;
    }

    public function existsWithEmail(Email $email): bool
    {
        return null !== $this->objectRepository->findOneBy(['email.value' => (string) $email]);
    }

    public function findByEmailOrFail(Email $email): User
    {
        if (null === ($user = $this->objectRepository->findOneBy(['email.value' => (string) $email]))) {
            throw UserNotFound::fromEmail((string) $email);
        }

        return $user;
    }

    public function findByGoogleIdOrFail(GoogleId $googleId): User
    {
        if (null === ($user = $this->objectRepository->findOneBy(['googleId.value' => (string) $googleId]))) {
            throw new \Exception();
        }

        return $user;
    }
}
