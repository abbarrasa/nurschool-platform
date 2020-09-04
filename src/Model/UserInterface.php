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


use FOS\UserBundle\Model\UserInterface as FOSUserInterface;

interface UserInterface extends FOSUserInterface
{
    public function getFirstname();
    public function setFirstname($firstname);
    public function getLastname();
    public function setLastname($lastname);
    public function getGoogleUid();
    public function setGoogleUid($googleUid);
    public function getFacebookUid();
    public function setFacebookUid($facebookUid);
    public function getVersion();
    public function setVersion(int $version);
    public function setCreatedAt(\DateTime $updatedAt);
    public function getCreatedAt();
    public function setUpdatedAt(\DateTime $updatedAt);
    public function getUpdatedAt();


}