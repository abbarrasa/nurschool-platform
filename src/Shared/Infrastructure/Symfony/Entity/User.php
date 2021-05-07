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

use Doctrine\ORM\Mapping as ORM;
use Nurschool\User\Domain\ValueObject\Credentials;
use Nurschool\User\Domain\Model\UserInterface;
use Nurschool\Core\Infrastructure\Persistence\Doctrine\Repository\UserDoctrineRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserDoctrineRepository::class)
 * @ORM\Table(name="nurschool_user")
 * @UniqueEntity(fields={"email"})
 */
final class User implements UserInterface, \Symfony\Component\Security\Core\User\UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="ulid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UlidGenerator::class)
     */
    private $id;

    /** @ORM\Embedded(class="Credentials::class") */
    private $credentials;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Version
     */
    private $version;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled = true;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(int $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getUsername(): string
    {
        return (string) $this->email;
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

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
