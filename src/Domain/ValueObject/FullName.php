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

namespace Nurschool\Platform\Domain\ValueObject;

use JsonSerializable;

final class FullName implements JsonSerializable
{
    private string $firstname;
    private string $lastname;

    /**
     * Fullname constructor.
     */
    public function __construct(string $firstname, string $lastname)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }

    public function firstname(): string
    {
        return $this->firstname;
    }

    public function lastname(): string
    {
        return $this->lastname;
    }
    
    public function changeFirstname(string $firstname)
    {
        $this->firstname = $firstname;
    }

    public function changeLastname(string $lastname)
    {
        $this->lastname = $lastname;
    }

    public function jsonSerialize(): array
    {
        return [
            'firstname' => $this->firstname(),
            'lastname' => $this->lastname()
        ];
    }

    public function __toString(): string
    {
        return trim("{$this->firstname} {$this->lastname}");
    }
}
