<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\User\Domain\Model\Security;


use Nurschool\User\Domain\Model\UserInterface;

interface UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user);

    public function checkPostAuth(UserInterface $user);

}