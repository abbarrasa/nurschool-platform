<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\User\Application\Command\Update;

use Nurschool\Shared\Application\Command\Command;
use Nurschool\User\Domain\User;
use Nurschool\User\Domain\ValueObject\Email;
use Nurschool\User\Domain\ValueObject\FullName;
use Nurschool\User\Domain\ValueObject\GoogleId;

class SetGoogleIdCommand implements Command
{
    public $user;
    public $googleId;
    public $fullName;

    public function __construct(User $user, GoogleId $googleId, FullName $fullName)
    {
        $this->user = $user;
        $this->googleId = $googleId;
        $this->fullName = $fullName;
    }

    static public function create(User $user, string $googleId, string $firstname, string $lastname): self
    {
        $googleId = GoogleId::fromString($googleId);
        $fullName = FullName::create($firstname, $lastname);

        return new self($user, $googleId, $fullName);
    }

}