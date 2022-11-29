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

namespace Nurschool\Platform\Infrastructure\Symfony\Api\Traits;

use Nurschool\Common\Application\Command\Command;
use Nurschool\Common\Application\Command\CommandBus;

trait CommandDispatchable
{
    /** @required */
    public CommandBus $commandBus;

    public function dispatch(Command $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
