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

namespace Nurschool\Shared\Domain;


use Nurschool\Shared\Domain\Event\DomainEvent;

abstract class AggregateRoot
{
    private $events = [];

    /**
     * @return DomainEvent[]
     */
    final public function pullDomainEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }

    final protected function record(DomainEvent $event): void
    {
        $this->events[] = $event;
    }
}