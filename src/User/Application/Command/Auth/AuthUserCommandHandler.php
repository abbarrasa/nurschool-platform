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

use Nurschool\Shared\Application\Command\CommandHandler;
use Nurschool\User\Domain\ValueObject\Credentials;

final class AuthUserCommandHandler implements CommandHandler
{
    private $authenticator;

    public function __construct(UserAuthenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    public function __invoke(AuthUserCommand $command): void
    {
        $this->authenticator->authenticate($command->credentials);
    }
}