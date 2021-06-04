<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\User\Infrastructure\Persistence\Doctrine\Entity;

use Gedmo\Timestampable\Traits\Timestampable;
use Nurschool\Shared\Domain\AggregateRoot;
use Nurschool\User\Domain\User as UserDomain;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class User extends AggregateRoot implements UserInterface
{
    use Timestampable;

    private const DEFAULT_ROLE = 'ROLE_USER';

//    /**
//     * @ORM\Column(type="integer")
//     * @ORM\Version
//     */
//    private $version;



//    public function getVersion(): ?int
//    {
//        return $this->version;
//    }
//
//    public function setVersion(int $version): self
//    {
//        $this->version = $version;
//
//        return $this;
//    }

    /**
     * @see \Symfony\Component\Security\Core\User\UserInterface
     */
    public function getRoles()
    {
        return [];
    }

    /**
     * @see \Symfony\Component\Security\Core\User\UserInterface
     */
    public function getPassword()
    {
        return $this->password()->toString();
    }

    /**
     * @see \Symfony\Component\Security\Core\User\UserInterface
     */
    public function getUsername()
    {
        return $this->email()->toString();
    }

    /**
     * @see \Symfony\Component\Security\Core\User\UserInterface
     * Not needed when using the "bcrypt" algorithm in security.yaml
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @see \Symfony\Component\Security\Core\User\UserInterface
     * Not needed when using the "bcrypt" algorithm in security.yaml
     */
    public function eraseCredentials()
    {
    }
}