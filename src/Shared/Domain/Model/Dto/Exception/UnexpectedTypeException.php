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

namespace Nurschool\Shared\Domain\Model\Dto\Exception;


class UnexpectedTypeException extends \RuntimeException
{
    private const CODE = 113;

    public function __construct($value, string $expectedType)
    {
        parent::__construct(
            sprintf('Expected argument of type "%s", "%s" given', $expectedType, get_debug_type($value)),
            self::CODE
        );
    }
}