<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Entity;


use FOS\UserBundle\Model\User as AbstractUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package Nurschool\Entity
 *
 * @ORM\Table(name="nurschool_user")
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User extends AbstractUser
{
    const ROLE_DEFAULT = 'ROLE_NURSE';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private $googleUid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private $facebookUid;

    public function __construct()
    {
        parent::__construct();
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getGoogleUid(): ?string
    {
        return $this->googleUid;
    }

    public function setGoogleUid(?string $googleUid): self
    {
        $this->googleUid = $googleUid;

        return $this;
    }

    public function getFacebookUid(): ?string
    {
        return $this->facebookUid;
    }

    public function setFacebookUid(?string $facebookUid): self
    {
        $this->facebookUid = $facebookUid;

        return $this;
    }
}