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

namespace Nurschool\Shared\Infrastructure\Bus\Command;


use Nurschool\Shared\Application\Command\Command;

final class CommandNotRegisteredException extends \RuntimeException
{
    public function __construct(Command $event)
    {
        $commandClass = get_class($event);

        parent::__construct("The command <$commandClass> hasn't a command handler associated");
    }
}