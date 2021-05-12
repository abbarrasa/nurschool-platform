<?php

declare(strict_types=1);

namespace Nurschool\User\Domain\ValueObject;

use Assert\Assertion;
use Nurschool\Shared\Application\Encoder\HashedPasswordEncoder;

final class HashedPassword
{
    private $password;

    private function __construct(string $hashedPassword)
    {
        $this->password = $hashedPassword;
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
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
        return (new HashedPasswordEncoder())->match($plainPassword, $this->password);
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    private static function hash(string $plainPassword): string
    {
//        Assertion::minLength($plainPassword, 6, 'Min 6 characters password');

        return (new HashedPasswordEncoder())->encode($plainPassword);
    }

    public function toString(): string
    {
        return $this->password;
    }

    public function __toString(): string
    {
        return $this->password;
    }
}