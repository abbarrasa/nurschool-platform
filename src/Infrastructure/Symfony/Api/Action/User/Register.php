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

namespace Nurschool\Platform\Infrastructure\Symfony\Api\Action\User;

use Nurschool\Platform\Application\Command\Create\CreateUserCommand;
use Nurschool\Platform\Infrastructure\Symfony\Api\ApiController;
use Nurschool\Platform\Infrastructure\Symfony\Api\Traits\CommandDispatchable;
use Symfony\Component\HttpFoundation\Request;

final class Register extends ApiController
{
    use CommandDispatchable;

    public function __invoke(Request $request)
    {
        $email = $this->getRequestParameter($request, 'email');
        $firstname = $this->getRequestParameter($request, 'firstname');
        $lastname = $this->getRequestParameter($request, 'lastname');
        $command = new CreateUserCommand($email, $firstname, $lastname);
        $this->dispatch($command);

        return [
            'email' => $email,
            'name' => $firstname
        ];
    }
}
