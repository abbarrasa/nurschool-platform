<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Nurschool\Shared\Application\Encoder;

use Nurschool\Shared\Domain\Service\Encoder\PasswordEncoderInterface;

class HashedPasswordEncoder implements PasswordEncoderInterface
{
    private const COST = 12;
    private const MAX_PASSWORD_LENGTH = 4096;

    /** @var string */
    private $algo;

    /** @var int */
    private $cost;

    /** @var int */
    private $maxLenght;

    public function __construct(
        string $algo = \PASSWORD_BCRYPT,
        int $cost = self::COST,
        int $maxLenght = self::MAX_PASSWORD_LENGTH
    ) {
        $this->algo = $algo;
        $this->cost = $cost;
        $this->maxLenght = $maxLenght;
    }
    
    public function encode(string $plainPassword): string
    {
        if ($this->isTooLong($plainPassword)) {
            throw new \RuntimeException('Password is too long.');
        }

        /** @var string|bool|null $hashedPassword */
        $hashedPassword = \password_hash($plainPassword, $this->algo, ['cost' => $this->cost]);

        if (\is_bool($hashedPassword)) {
            throw new \RuntimeException('Server error hashing password.');
        }

        return (string) $hashedPassword;
    }

    public function match(string $plainPassword, string $encodedPassword): bool
    {
        return \password_verify($plainPassword, $this->hashedPassword);
    }

    public function needsRehash(string $encodedPassword): bool
    {
        return \password_needs_rehash($encodedPassword, $this->algo, ['cost' => $this->cost]);
    }

    private function isTooLong(string $plainPassword): bool
    {
        return \strlen($plainPassword) > $this->maxLenght ||
            (\PASSWORD_BCRYPT === $this->algo && 72 < \strlen($plainPassword))
        ;
    }
}