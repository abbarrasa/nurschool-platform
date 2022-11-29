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

use Nurschool\Platform\Domain\Service\Checker\PasswordChecker;
use Nurschool\Platform\Domain\Service\Encoder\PasswordEncoder;

final class HashedPassword
{
    private $value;

    private function __construct(string $hashedPassword)
    {
        $this->value = $hashedPassword;
    }

    public static function encode(
        string $plainPassword,
        PasswordEncoder $passwordEncoder,
        PasswordChecker $passwordChecker
    ): self {
        $passwordChecker->ensureIsValidPassword($plainPassword);

        return new self($passwordEncoder->encode($plainPassword));
    }

    /*public static function fromHash(string $hashedPassword): self
    {
        return new self($hashedPassword);
    }*/

    public function match(string $plainPassword, PasswordEncoder $passwordEncoder): bool
    {
        return $passwordEncoder->match($plainPassword, $this->value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
