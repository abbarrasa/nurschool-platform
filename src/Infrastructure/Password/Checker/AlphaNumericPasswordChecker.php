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

namespace Nurschool\Platform\Infrastructure\Password\Checker;

use Nurschool\Platform\Application\Service\Password\Checker\Exception\InvalidPassword;
use Nurschool\Platform\Application\Service\Password\Checker\PasswordChecker;

final class AlphaNumericPasswordChecker implements PasswordChecker
{
    private const MIN_LENGTH = 6;
    private const MIN_NUMERIC_CHARACTERS = 0;
    private const MIN_UPPERCASE_CHARACTERS = 0;
    private const MIN_LOWERCASE_CHARACTERS = 1;

    private int $minLength;
    private int $minNumericCharacters;
    private $minLowercaseCharacters;
    private int $minUppercaseCharacters;

    public function __construct(
        int $minLength = self::MIN_LENGTH,
        int $minNumericCharacters = self::MIN_NUMERIC_CHARACTERS,
        int $minLowercaseCharacters = self::MIN_LOWERCASE_CHARACTERS,
        int $minUppercaseCharacters = self::MIN_UPPERCASE_CHARACTERS
    ) {
        $this->minLength = $minLength;
        $this->minNumericCharacters = $minNumericCharacters;
        $this->minLowercaseCharacters = $minLowercaseCharacters;
        $this->minUppercaseCharacters = $minUppercaseCharacters;
    }

    public static function instance(
        int $minLength = self::MIN_LENGTH,
        int $minNumericCharacters = self::MIN_NUMERIC_CHARACTERS,
        int $minLowercaseCharacters = self::MIN_LOWERCASE_CHARACTERS,
        int $minUppercaseCharacters = self::MIN_UPPERCASE_CHARACTERS
    ): self {
        return new self(
            $minLength,
            $minNumericCharacters,
            $minLowercaseCharacters,
            $minUppercaseCharacters
        );
    }

    public function ensureIsValidPassword(string $plainPassword): bool
    {
        if (strlen($plainPassword) < $this->minLength) {
            throw InvalidPassword::createTooShortFail($this->minLength);
        }

        if ($this->minNumericCharacters > 0 &&
            !preg_match("/[0-9]{{$this->minNumericCharacters}}/", $plainPassword)
        ) {
            throw InvalidPassword::createNumericCharactersFail($this->minNumericCharacters);
        }

        if ($this->minLowercaseCharacters > 0 &&
            !preg_match("/[a-z]{{$this->minLowercaseCharacters}}/", $plainPassword)
        ) {
            throw InvalidPassword::createLowercaseCharactersFail($this->minLowercaseCharacters);
        }

        if ($this->minUppercaseCharacters > 0 &&
            !preg_match("/[A-Z]{{$this->minUppercaseCharacters}}/", $plainPassword)
        ) {
            throw InvalidPassword::createUppercaseCharactersFail($this->minUppercaseCharacters);
        }

        return true;
    }
}
