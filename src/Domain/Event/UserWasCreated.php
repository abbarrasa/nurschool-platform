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

namespace Nurschool\Platform\Domain\Event;

use Nurschool\Common\Domain\Event\DomainEvent;
use Nurschool\Common\Domain\ValueObject\Uuid;
use Nurschool\Platform\Domain\ValueObject\Email;
use Nurschool\Platform\Domain\ValueObject\FullName;

final class UserWasCreated extends DomainEvent
{
    private const NAME = 'user.created';

    private string $email;
    private string $firstname;
    private string $lastname;
    private bool $enabled;
    
    public function __construct(Uuid $id, Email $email, FullName $fullName, bool $enabled)
    {
        parent::__construct((string)$id);
        $this->email = (string)$email;
        $this->firstname = $fullName->firstname();
        $this->lastname = $fullName->lastname();
        $this->enabled = $enabled;
    }

    public function getId(): string
    {
        return $this->aggregateId;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Get the value of firstname
     */ 
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * Get the value of lastname
     */ 
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * Get the value of enabled
     */ 
    public function getEnabled(): bool
    {
        return $this->enabled;
    }    
    
    public static function eventName(): string
    {
        return self::NAME;
    }
}