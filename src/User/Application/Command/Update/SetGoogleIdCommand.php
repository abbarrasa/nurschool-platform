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
    /** @var User */
    public $user;

    /** @var string */
    public $googleId;

    /** @var string */
    public $firstname;

    /** @var string */
    public $lastname;

    public function __construct(User $user, string $googleId)
    {
        $this->user = $user;
        $this->googleId = $googleId;
    }
}