<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\User\Domain\Model\Dto;


use Nurschool\User\Infrastructure\Validator\Constraints\Password;
use Nurschool\User\Infrastructure\Validator\Constraints\UniqueEmail;

final class UserDto
{
    /**
     * @var string
     * @UniqueEmail()
     */
    public $email;

    /**
     * @var string
     */
    public $firstname;

    /**
     * @var string
     */
    public $lastname;

    /**
     * @var string
     * @Password()
     */
    public $password;

    /**
     * @var array
     */
    public $roles = [];
}