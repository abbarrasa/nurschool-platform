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

namespace Nurschool\User\Domain\ValueObject\Auth;


use Nurschool\User\Domain\ValueObject\Email;

class Credentials
{
    public $email;

    public $plainPassword;

    public function __construct(Email $email, string $plainPassword)
    {
        $this->email = $email;
        $this->plainPassword = $plainPassword;
    }

    public static function create(string $email, string $plainPassword): self
    {
        return new self(Email::fromString($email), $plainPassword);
    }
}