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


trait CodableEntity
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $code;

    /**
     * @ORM\Column(type="datetime_inmutable")
     */
    protected $expiresAt;

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }

    /**
     * @param \DateTimeInterface $expiresAt
     * @return $this
     */
    public function setExpiresAt(\DateTimeInterface $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }
}