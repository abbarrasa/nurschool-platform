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

namespace Nurschool\Shared\Domain\Service\Encoder;


interface PasswordEncoderInterface
{
    public function encode(string $plainPassword): string;

    public function match(string $plainPassword, string $encodedPassword): bool;

    public function needsRehash(string $encodedPassword): bool;
}