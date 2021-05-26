<?php


namespace Nurschool\User\Domain;


use Nurschool\Shared\Domain\AggregateRoot;
use Nurschool\Shared\Infrastructure\Symfony\Event\UserCreated;
use Nurschool\User\Domain\ValueObject\Email;
use Nurschool\User\Domain\ValueObject\FullName;
use Nurschool\User\Domain\ValueObject\HashedPassword;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class User extends AggregateRoot
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

        $this->record(UserCreated::fromPrimitives($this->id, []));
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

    public function fullName(): FullName
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

    public function updateLastLogin(\DateTimeInterface $lastLogin): void
    {
        $this->lastLogin = $lastLogin;
    }
}