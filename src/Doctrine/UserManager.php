<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Doctrine;


use FOS\UserBundle\Doctrine\UserManager as FOSUserManager;
use Nurschool\Model\UserManagerInterface;

class UserManager extends FOSUserManager implements UserManagerInterface
{
    public function findUserByPhone($phone)
    {
        // TODO: Implement findUserByPhone() method.
    }

    public function findUserByGoogleUid($googleUid)
    {
        return $this->findUserBy(['googleUid' => $googleUid]);
    }

    public function findUserByFacebookUid($facebookUid)
    {
        return $this->findUserBy(['facebookUid' => $facebookUid]);
    }
}