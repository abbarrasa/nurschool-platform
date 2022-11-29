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

namespace Nurschool\Platform\Domain\Exception;

use Nurschool\Common\Domain\Exception\Exception;

class InvalidEmail extends Exception
{
    protected string $codification = '';

    public static function createFromEmail(string $email): self
    {
        return new self(\sprintf('"%s" is not a valid email', $email));
    }
}
