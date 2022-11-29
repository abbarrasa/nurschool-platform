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

namespace Nurschool\Platform\Application\Service\Password\Checker\Exception;

use Nurschool\Common\Domain\Exception\Exception;

class InvalidPassword extends Exception
{
    private const CODIFICATION_TOO_SHORT = '';
    private const CODIFICATION_TOO_LONG = '';

    public static function createTooShortFail(int $minLenght): self
    {
        return (new self(\sprintf('Must have at least %d characters', $minLenght)))
            ->setCodification(self::CODIFICATION_TOO_SHORT)
        ;
    }

    public static function createTooLongFail(int $maxLenght): self
    {
        return (new self(\sprintf('Must have at %d characters or less', $maxLenght)))
            ->setCodification(self::CODIFICATION_TOO_LONG)
        ;
    }

    public static function createNumericCharactersFail(int $minNumericCharacters): self
    {
        return new self(\sprintf('Must have at least %d numeric characters', $minNumericCharacters));
    }

    public static function createLowercaseCharactersFail(int $minLowercaseCharacters): self
    {
        return new self(\sprintf('Must have at least %d lowercase characters', $minLowercaseCharacters));
    }

    public static function createUppercaseCharactersFail(int $minUppercaseCharacters): self
    {
        return new self(\sprintf('Must have at least %d uppercase characters', $minUppercaseCharacters));
    }

    public static function createNonAlphaNumericCharactersFail(int $minNonAlphaNumericCharacters): self
    {
        return new self(\sprintf('Must have at least %d non alphanumeric characters', $minNonAlphaNumericCharacters));
    }
}
