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


use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface as FOSUserManagerInterface;


interface UserManagerInterface extends FOSUserManagerInterface
{
    /**
     * Finds a user by its username or email.
     * @param $phoneOrEmail
     * @return UserInterface|null
     */
//    public function findUserByPhoneOrEmail($phoneOrEmail);

    /**
     * Finds a user by its phone.
     * @param $phone
     * @return UserInterface|null
     */
    public function findUserByPhone($phone);

    /**
     * Finds a user by its Google User ID
     * @param $googleUid
     * @return UserInterface|null
     */
    public function findUserByGoogleUid($googleUid);

    /**
     * Finds a user by its Facebook User ID
     * @param $facebookUid
     * @return UserInterface|null
     */
    public function findUserByFacebookUid($facebookUid);
}