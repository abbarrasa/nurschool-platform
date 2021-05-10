<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Shared\Infrastructure\Bus\Event;


use Nurschool\Shared\Application\Event\DomainEventInterface;

class EventNotRegisteredException extends \RuntimeException
{
    public function __construct(DomainEventInterface $event)
    {
        $eventClass = get_class($event);

        parent::__construct("The event <$eventClass> hasn't a event handler associated");
    }

}