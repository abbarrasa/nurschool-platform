<?php

namespace Nurschool\Entity;

use Nurschool\Repository\GroupRepository;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\Group as FOSGroup;

/**
 * @ORM\Entity(repositoryClass=GroupRepository::class)
 * @ORM\Table(name="nurschool_group")
 */
class Group extends FOSGroup
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
