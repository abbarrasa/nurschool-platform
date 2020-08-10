<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Model;


use FOS\UserBundle\Model\UserManagerInterface as FOSUserManagerInterface;


interface UserManagerInterface extends FOSUserManagerInterface
{
    /**
     * Finds a user by its phone.
     * @param $phone
     */
    public function findUserByPhone($phone);

    /**
     * Finds a user by its Google User ID
     * @param $googleUid
     */
    public function findUserByGoogleUid($googleUid);

    /**
     * Finds a user by its Facebook User ID
     * @param $facebookUid
     */
    public function findUserByFacebookUid($facebookUid);
}