<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Shared\Domain\Event;


use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class DomainEvent
{
    /** @var UuidInterface */
    private $eventId;

    private $aggregateId;

    private $occurredOn;

    public function __construct(string $aggregateId, UuidInterface $eventId, \DateTimeInterface $occurredOn)
    {
        $this->aggregateId = $aggregateId;
        $this->eventId = $eventId ?: Uuid::uuid4();
        $this->occurredOn = $occurredOn ?: new \DateTime();
    }

    abstract public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): self;

    abstract public static function eventName(): string;

    abstract public function toPrimitives(): array;

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function occurredOn(): string
    {
        return $this->occurredOn;
    }
}