<?php

declare(strict_types=1);

namespace Nurschool\User\Domain\ValueObject;

use Assert\Assertion;
use Nurschool\User\Application\Encoder\HashedPasswordEncoder;

final class HashedPassword
{
    private $value;

    public function __construct(string $hashedPassword)
    {
        $this->value = $hashedPassword;
    }

    public static function encode(string $plainPassword): self
    {
        return new self(self::hash($plainPassword));
    }

    public static function fromHash(string $hashedPassword): self
    {
        return new self($hashedPassword);
    }

    public function match(string $plainPassword): bool
    {
        return (new HashedPasswordEncoder())->match($plainPassword, $this->value);
    }

    private static function hash(string $plainPassword): string
    {
        return HashedPasswordEncoder::instance()->encode($plainPassword);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}