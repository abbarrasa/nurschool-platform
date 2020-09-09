<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class Password extends Constraint
{
    // max length allowed by Symfony for security reasons is 4096
    public $pattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,4096}$/';
    public $message = 'password.invalid';
}