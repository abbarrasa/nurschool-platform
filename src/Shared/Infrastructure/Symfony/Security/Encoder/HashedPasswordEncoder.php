<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Shared\Infrastructure\Symfony\Security\Encoder;


use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Nurschool\Shared\Domain\Service\Encoder\PasswordEncoderInterface as PasswordEncoder;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class HashedPasswordEncoder implements PasswordEncoderInterface
{
    /** @var PasswordEncoder */
    private $encoder;

    public function __construct(PasswordEncoder $encoder)
    {
        $this->encoder = $encoder;
    }

    public function encodePassword(string $raw, ?string $salt)
    {
        try {
            return $this->encoder->encode($raw);
        } catch (\Exception $exception) {
            throw new BadCredentialsException('Invalid password.');
        }
    }

    public function isPasswordValid(string $encoded, string $raw, ?string $salt)
    {
        if ('' === $raw) {
            return false;
        }

        return $this->isPasswordValid($raw, $encoded);
    }

    public function needsRehash(string $encoded): bool
    {
        return $this->encoder->needsRehash($encoded);
    }
}