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
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Nurschool\Model\UserInterface;
use Nurschool\Repository\UserRepository;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="nurschool_user")
 */
class User extends AbstractUser implements UserInterface
{
    /**
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
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

    /**
     * @ORM\Column(type="integer")
     * @ORM\Version
     */
    private $version;

    /**
     * @ORM\ManyToMany(targetEntity="Nurschool\Entity\Group")
     * @ORM\JoinTable(name="nurschool_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getGoogleUid()
    {
        return $this->googleUid;
    }

    public function setGoogleUid($googleUid)
    {
        $this->googleUid = $googleUid;

        return $this;
    }

    public function getFacebookUid()
    {
        return $this->facebookUid;
    }

    public function setFacebookUid($facebookUid)
    {
        $this->facebookUid = $facebookUid;

        return $this;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(int $version): self
    {
        $this->version = $version;

        return $this;
    }
}