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

namespace Nurschool\Platform\Domain;

use Nurschool\Common\Domain\AggregateRoot;
use Nurschool\Common\Domain\ValueObject\Uuid;
use Nurschool\Platform\Domain\Event\UserWasCreated;
use Nurschool\Platform\Domain\Specification\UniqueEmail;
use Nurschool\Platform\Domain\ValueObject\Avatar;
use Nurschool\Platform\Domain\ValueObject\Email;
use Nurschool\Platform\Domain\ValueObject\FullName;
use Nurschool\Platform\Domain\ValueObject\GoogleId;
use Nurschool\Platform\Domain\ValueObject\HashedPassword;

/**
 * Class User.
 */
class User extends AggregateRoot
{
    private Uuid $id;
    private Email $email;
    private HashedPassword $password;
    private FullName $fullName;
    private GoogleId $googleId;
    /*private \DateTimeInterface $lastLogin;*/
    private bool $enabled = false;
    private Avatar $avatar;

    public static function createFromEmail(
        Email $email,
        FullName $fullName,
        UniqueEmail $uniqueEmail
    ): self
    {
        $uniqueEmail->isSatisfiedBy($email);

        $self = new self();
        $self->id = Uuid::random();
        $self->email = $email;
        $self->fullName = $fullName;
        $self->record(new UserWasCreated(
            $self->id(),
            $self->email(),
            $self->fullName(),
            $self->isEnabled()
        ));

        return $self;
    }

    public static function createFromGoogleId(
        Email $email,
        GoogleId $googleId,
        FullName $fullName,
        ?Avatar $avatar,
        UniqueEmail $uniqueEmail
    ): self
    {
        $self = self::createFromEmail($email, $fullName, $uniqueEmail);
        $self
            ->updateGoogleId($googleId)
            ->updateAvatar($avatar)
            ->enable()
        ;

        return $self;
    }  

    public function id(): Uuid
    {
        return $this->id;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function fullName(): FullName
    {
        return $this->fullName;
    }

    public function avatar(): ?Avatar
    {
        return $this->avatar;
    }

    public function googleId(): GoogleId
    {
        return $this->googleId;
    }
   
    public function rename(FullName $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function updateAvatar(Avatar $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function updateGoogleId(GoogleId $googleId): self
    {
        $this->googleId = $googleId;

        return $this;
    }

    /*

    public function lastLogin(): \DateTimeInterface
    {
        return $this->lastLogin;
    }

    */

    public function password(): HashedPassword
    {
        return $this->password;
    }

    public function updatePassword(HashedPassword $hashedPassword): self
    {
        $this->password = $hashedPassword;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function enable(): self
    {
        $this->enabled = true;

        return $this;
    }

    public function disable(): self
    {
        $this->enabled = false;

        return $this;
    }

    /*public function updateLastLogin(?\DateTimeInterface $lastLoginDate = null): self
    {
        if (null === $lastLoginDate) {
            $lastLoginDate = new \DateTimeImmutable();
        }

        $this->lastLogin = $lastLoginDate;

        return $this;
    }*/
}
