<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\User\Infrastructure\Symfony\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueEmail
 * @package Nurschool\User\Infrastructure\Validator\Constraints
 *
 * @Annotation()
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class UniqueEmail extends Constraint
{
    public const NOT_UNIQUE_ERROR = '33528a26-afc7-468b-9830-ec13d426a3b2';

    public $message = 'user.email.not_unique.';
}