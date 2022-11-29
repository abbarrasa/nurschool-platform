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

final class GoogleId
{
    /** @var string */
    private $value;

    /**
     * Email constructor.
     */
    public function __construct(string $googleId)
    {
        $this->value = $googleId;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
