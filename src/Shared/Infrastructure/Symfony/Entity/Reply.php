<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Shared\Infrastructure\Symfony\Entity;

use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReplyDoctrineRepository::class)
 * @ORM\Table(name="nurschool_reply")
 */
final class Reply
{
    /**
     * @ORM\Id
     * @ORM\Column(type="ulid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UlidGenerator::class)
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $text;

    /**
     * @ORM\OneToMany(targetEntity=Nurschool\Forum\Infrastructure\Symfony\Entity\Discussion, orphanRemoval=true)
     */
    private $discussion;

    /**
     * @ORM\ManyToOne(targetEntity="Reply", inversedBy="replies")
     */
    private $repliedTo;

    /**
     * @ORM\OneToMany(targetEntity="Reply", mappedBy="repliedTo")
     */
    protected $replies;
}