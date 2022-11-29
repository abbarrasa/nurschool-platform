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
use Nurschool\Platform\Domain\Exception\InvalidEmail;

final class Email implements JsonSerializable
{
    private string $value;

    /**
     * Email constructor.
     */
    private function __construct(string $email)
    {
        $this->value = $email;
    }

    public static function fromString(string $email): self
    {
        if (!\filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw InvalidEmail::createFromEmail($email);
        }

        return new self($email);
    }

    public function jsonSerialize(): string
    {
        return (string) $this;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
