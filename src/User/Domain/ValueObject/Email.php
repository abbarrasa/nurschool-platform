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

namespace Nurschool\User\Domain\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

final class Email implements \JsonSerializable
{
    /** @var string */
    private $value;

    /**
     * Email constructor.
     * @param string $email
     */
    public function __construct(string $email)
    {
        $this->value = $email;
    }

    /**
     * @throws AssertionFailedException
     */
    public static function fromString(string $email): self
    {
//        Assertion::email($email, 'Not a valid email');

        return new self($email);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->toString();
    }
}