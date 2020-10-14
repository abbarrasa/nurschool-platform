<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Model;


interface TokenableInterface
{
    public function getSelector(): string;

    public function setSelector(string $selector);

    public function getRequestedAt(): \DateTimeInterface;

    public function setRequestedAt(\DateTimeInterface $requestedAt);

    public function getExpiresAt(): \DateTimeInterface;

    public function setExpiresAt(\DateTimeInterface $expiresAt);

    public function isExpired(): bool;
}