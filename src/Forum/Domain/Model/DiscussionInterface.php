<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Forum\Domain\Model;


use Symfony\Component\Uid\Ulid;

interface DiscussionInterface
{
    public function getId(): ?Ulid;

    public function getTitle(): ?string;

    public function setTitle(string $title);

    public function getSlug(): ?string;

    public function setSlug(string $slug);

    public function setCreatedAt(\DateTime $createdAt);

    /**
     * Returns createdAt.
     */
    public function getCreatedAt();

    /**
     * Sets updatedAt.
     */
    public function setUpdatedAt(\DateTime $updatedAt);

    /**
     * Returns updatedAt.
     */
    public function getUpdatedAt();
}