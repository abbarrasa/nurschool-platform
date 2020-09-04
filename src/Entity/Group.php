<?php

namespace Nurschool\Entity;

use Nurschool\Repository\GroupRepository;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\Group as FOSUserGroup;

/**
 * @ORM\Entity(repositoryClass=GroupRepository::class)
 * @ORM\Table(name="nurschool_group")
 */
class Group extends FOSUserGroup
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
