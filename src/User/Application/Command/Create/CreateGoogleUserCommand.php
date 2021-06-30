<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\User\Application\Command\Create;


use Nurschool\Shared\Application\Command\Command;
use Nurschool\User\Domain\ValueObject\Email;
use Nurschool\User\Domain\ValueObject\FullName;
use Nurschool\User\Domain\ValueObject\GoogleId;

class CreateGoogleUserCommand implements Command
{
    public $email;
    public $googleId;
    public $fullName;

    public function __construct(Email $email, GoogleId $googleId, FullName $fullName)
    {
        $this->email = $email;
        $this->googleId = $googleId;
        $this->fullName = $fullName;
    }

    static public function create(string $email, string $googleId, string $firstname, string $lastname): self
    {
        $email = Email::fromString($email);
        $googleId = GoogleId::fromString($googleId);
        $fullName = FullName::create($firstname, $lastname);

        return new self($email, $googleId, $fullName);
    }
}