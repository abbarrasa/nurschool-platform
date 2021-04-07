<?php


namespace Nurschool\Shared\Infrastructure\Symfony\Entity;

/**
 * @ORM\Entity(repositoryClass=SchoolDoctrineRepository::class)
 * @ORM\Table(name="nurschool_school")
 */
class School
{
    /**
     * @ORM\Id
     * @ORM\Column(type="ulid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UlidGenerator::class)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;
}