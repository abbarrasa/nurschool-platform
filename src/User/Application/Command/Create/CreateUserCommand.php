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


use Nurschool\Shared\Application\Command\Command;
use Nurschool\User\Domain\ValueObject\Email;
use Nurschool\User\Domain\ValueObject\HashedPassword;

final class CreateUserCommand implements Command
{
    /** @var Email */
    private $email;

    private $hashedPassword;

    public function __construct(string $email, string $plainPassword)
    {
        $this->email = Email::fromString($email);
        $this->hashedPassword = HashedPassword::encode($plainPassword);
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = Email::fromString($email);
    }

    /**
     * @return HashedPassword
     */
    public function getHashedPassword(): HashedPassword
    {
        return $this->hashedPassword;
    }

    /**
     * @param HashedPassword $hashedPassword
     */
    public function setHashedPassword(HashedPassword $hashedPassword): void
    {
        $this->hashedPassword = $hashedPassword;
    }
}