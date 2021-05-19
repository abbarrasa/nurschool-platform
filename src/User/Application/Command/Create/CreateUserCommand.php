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
    public $email;

    /** @var HashedPassword */
    public $hashedPassword;

    public function __construct(Email $email, HashedPassword $hashedPassword)
    {
        $this->email = $email;
        $this->hashedPassword = $hashedPassword;
    }

    public static function create(string $email, string $plainPassword): self
    {
        $email = Email::fromString($email);
        $hashedPassword = HashedPassword::encode($plainPassword);

        return new self($email, $hashedPassword);
    }
}