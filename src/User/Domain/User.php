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

namespace Nurschool\User\Domain;

use Nurschool\Shared\Infrastructure\Symfony\Model\User as UserModel;
use Nurschool\User\Domain\Event\UserCreated;
use Nurschool\User\Domain\ValueObject\Email;
use Nurschool\User\Domain\ValueObject\FullName;
use Nurschool\User\Domain\ValueObject\HashedPassword;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class User extends UserModel
{
    /** @var UuidInterface */
    private $id;

    /** @var Email */
    private $email;

    /** @var HashedPassword */
    private $password;

    /** @var FullName */
    private $fullName;

    /** @var \DateTimeInterface */
    private $lastLogin;

    /** @var bool */
    private $enabled = false;

    private function __construct(UuidInterface $id, Email $email, HashedPassword $password)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;

        $this->record(UserCreated::fromPrimitives($this->id->toString(), []));
    }

    public static function create(Email $email, HashedPassword $password): self
    {
        return new self(Uuid::uuid4(), $email, $password);
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): HashedPassword
    {
        return $this->password;
    }

    public function fullName(): FullNam
    {
        return $this->fullName;
    }

    public function rename(FullName $fullName)
    {
        $this->fullName = $fullName;
    }

    public function enable(): void
    {
        $this->enabled = true;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function disable(): void
    {
        $this->enabled = false;
    }

    public function lastLogin(): \DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function updateLastLogin(?\DateTimeInterface $lastLogin = null): void
    {
        if (null === $lastLogin) {
            $lastLogin = new \DateTimeImmutable();
        }

        $this->lastLogin = $lastLogin;
    }
}