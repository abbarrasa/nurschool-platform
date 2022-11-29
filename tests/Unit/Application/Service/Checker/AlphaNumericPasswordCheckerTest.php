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

namespace Nurschool\Platform\Tests\Unit\Application\Service\Checker;

use Nurschool\Platform\Application\Service\Password\Checker\Exception\InvalidPassword;
use Nurschool\Platform\Infrastructure\Password\Checker\AlphaNumericPasswordChecker;
use PHPUnit\Framework\TestCase;

class AlphaNumericPasswordCheckerTest extends TestCase
{
    private AlphaNumericPasswordChecker $passwordChecker;

    public function setUp(): void
    {
        $this->passwordChecker = new AlphaNumericPasswordChecker(8, 1, 1, 1);
    }

    public function testTooShortFail()
    {
        $planPassword = 'pass';
        $this->expectException(InvalidPassword::class);
        $this->passwordChecker->ensureIsValidPassword($planPassword);
    }

    public function testNumericCharactersFail()
    {
        $planPassword = 'password';
        $this->expectException(InvalidPassword::class);
        $this->passwordChecker->ensureIsValidPassword($planPassword);
    }

    public function testLowercaseCharacters()
    {
        $planPassword = 'P4SSW0RD';
        $this->expectException(InvalidPassword::class);
        $this->passwordChecker->ensureIsValidPassword($planPassword);
    }

    public function testUppercaseCharacters()
    {
        $planPassword = 'p4ssw0rd';
        $this->expectException(InvalidPassword::class);
        $this->passwordChecker->ensureIsValidPassword($planPassword);
    }

    public function testIsValidPassword()
    {
        $plainPassword = 'P4ssw0rd';
        $this->assertTrue($this->passwordChecker->ensureIsValidPassword($plainPassword));
    }
}