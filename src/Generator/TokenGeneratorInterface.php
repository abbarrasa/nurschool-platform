<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Generator;


use Nurschool\Model\TokenComponents;

interface TokenGeneratorInterface
{
    public function createToken(string $data, \DateTimeInterface $expiresAt = null, string $verifier = null): TokenComponents;
}