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

namespace Nurschool\User\Application\Command\Auth;


use Nurschool\Shared\Application\Command\Command;
use Nurschool\User\Domain\ValueObject\Email;

final class AuthUserCommand implements Command
{
    /** @var Email */
    public $email;

    /** @var string */
    public $plainPassword;

    public function __construct(Email $email, string $plainPassword)
    {
        $this->email = $email;
        $this->plainPassword = $plainPassword;
    }

    public static function create(string $email, string $plainPassword): self
    {
        $email = Email::fromString($email);
        return new self($email, $plainPassword);
    }
}