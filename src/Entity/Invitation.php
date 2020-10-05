<?php

namespace Nurschool\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Nurschool\Repository\InvitationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InvitationRepository::class)
 * @ORM\Table(name="nurschool_invitation")
 */
class Invitation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="boolean")
     */
    private $sent = false;

    /**
     * @ORM\ManyToMany(targetEntity=School::class, inversedBy="invitations")
     * @ORM\JoinTable(name="nurschool_invitation_school")
     */
    private $schools;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $host;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="invitation", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->schools = new ArrayCollection();
        // generate identifier only once, here a 8 characters length code
        $this->code = substr(md5(uniqid(rand(), true)), 0, 8);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
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

    public function getSent(): ?bool
    {
        return $this->sent;
    }

    public function setSent(bool $sent): self
    {
        $this->sent = $sent;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return Collection|School[]
     */
    public function getSchools(): Collection
    {
        return $this->schools;
    }

    public function addSchool(School $school): self
    {
        if (!$this->schools->contains($school)) {
            $this->schools[] = $school;
        }

        return $this;
    }

    public function removeSchool(School $school): self
    {
        if ($this->schools->contains($school)) {
            $this->schools->removeElement($school);
        }

        return $this;
    }

    public function getHost(): ?User
    {
        return $this->host;
    }

    public function setHost(User $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
