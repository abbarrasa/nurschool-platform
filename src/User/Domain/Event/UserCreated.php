<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\User\Domain\Event;

use Nurschool\Shared\Domain\Event\DomainEvent;

final class UserCreated extends DomainEvent
{
    private const NAME = 'user.created';

    public static function fromPrimitives(string $aggregateId, array $body, string $eventId, string $occurredOn): DomainEvent
    {
        // TODO: Implement fromPrimitives() method.
    }

    public function toPrimitives(): array
    {
        // TODO: Implement toPrimitives() method.
    }

    public static function eventName(): string
    {
        return self::NAME;
    }
}