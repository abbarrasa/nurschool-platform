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


interface InvitationCodeGeneratorInterface
{
    public function createCode(): string;

    public function createToken(\DateTimeInterface $expiresAt, string $code);
}