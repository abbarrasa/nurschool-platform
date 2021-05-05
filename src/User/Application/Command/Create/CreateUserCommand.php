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


use Nurschool\Shared\Application\Command\CommandInterface;
use Nurschool\User\Domain\ValueObject\Email;

final class CreateUserCommand implements CommandInterface
{
    /** @var Email */
    private $email;

    /** @var string */
    private $password;

    public function __construct(string $email, string $password)
    {
        $this->email = Email::fromString($email);
        $this->password = $password;
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
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}