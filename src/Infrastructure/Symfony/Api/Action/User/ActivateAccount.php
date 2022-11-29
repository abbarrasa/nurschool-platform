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

use Nurschool\Platform\Application\Command\User\ActivateAccount\ActivateAccountCommand;
use Nurschool\Platform\Infrastructure\Symfony\Api\ApiController;
use Nurschool\Platform\Infrastructure\Symfony\Api\Traits\CommandDispatchable;
use Symfony\Component\HttpFoundation\Request;

final class ActivateAccount extends ApiController
{
    use CommandDispatchable;

    public function __invoke(Request $request, string $id)
    {
        $url = $request->getUri();
        $command = new ActivateAccountCommand($id, $url);
        $this->dispatch($command);
    }
}
