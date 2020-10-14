<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Entity\Traits;


trait TokenableEntity
{
    public static $_SELECTOR_LENGTH = 20;

    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $selector;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $requestedAt;

    /**
     * @ORM\Column(type="datetime_inmutable, nullable=true")
     */
    protected $expiresAt;

    public function getSelector(): string
    {
        return $this->selector;
    }

    public function setSelector(string $selector)
    {
        $this->selector = $selector;

        return $this;
    }

    public function getRequestedAt(): \DateTimeInterface
    {
        return $this->requestedAt;
    }

    public function setRequestedAt(\DateTimeInterface $requestedAt)
    {
        $this->requestedAt = $requestedAt;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?\DateTimeInterface $expiresAt)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function isExpired(): bool
    {
        return (null !== $this->expiresAt) && ($this->expiresAt->getTimestamp() <= \time());
    }
}