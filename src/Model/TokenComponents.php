<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Model;


class TokenComponents
{
    /** @var \DateTimeInterface|null */
    private $expiresAt;

    /** @var string */
    private $selector;

    /** @var string */
    private $token;


    public function __construct(string $token, string $selector, \DateTimeInterface $expiresAt = null)
    {
        $this->token = $token;
        $this->selector = $selector;
        $this->expiresAt = $expiresAt;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getSelector(): string
    {
        return $this->selector;
    }

    /**
     * @param string $selector
     */
    public function setSelector(string $selector): void
    {
        $this->selector = $selector;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @param \DateTimeInterface|null $expiresAt
     */
    public function setExpiresAt(?\DateTimeInterface $expiresAt)
    {
        $this->expiresAt = $expiresAt;
    }
}