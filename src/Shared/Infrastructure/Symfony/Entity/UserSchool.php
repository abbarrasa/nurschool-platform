<?php


namespace Nurschool\Shared\Infrastructure\Symfony\Entity;

use Nurschool\Core\Infrastructure\Symfony\Entity\User;
use Nurschool\Core\Infrastructure\Symfony\Entity\School;

/**
 * @ORM\Entity()
 * @ORM\Table(name="nurschool_user_school")
 */
class UserSchool
{
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="User::class")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="School::class")
     * @ORM\JoinColumn(name="id_school", referencedColumnName="id")
     */
    private $school;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];
}