<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Nurschool\Repository\SchoolRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SchoolRepository::class)
 * @ORM\Table(name="nurschool_school")
 */
class School
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="schools")
     * @ORM\JoinTable(name="nurschool_school_user")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=JoinSchoolRequest::class, mappedBy="school", orphanRemoval=true)
     */
    private $joinSchoolRequests;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->joinSchoolRequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getAdministrators(): Collection
    {
        return $this->users->filter(function(User $u) { return $u->hasRole('ROLE_ADMIN'); });
    }

    /**
     * @return Collection|User[]
     */
    public function getNursers(): Collection
    {
        return $this->users->filter(function(User $u) { return $u->hasRole('ROLE_NURSE'); });
    }

    /**
     * @return Collection|JoinSchoolRequest[]
     */
    public function getJoinSchoolRequests(): Collection
    {
        return $this->joinSchoolRequests;
    }

    public function addJoinSchoolRequest(JoinSchoolRequest $joinSchoolRequest): self
    {
        if (!$this->joinSchoolRequests->contains($joinSchoolRequest)) {
            $this->joinSchoolRequests[] = $joinSchoolRequest;
            $joinSchoolRequest->setSchool($this);
        }

        return $this;
    }

    public function removeJoinSchoolRequest(JoinSchoolRequest $joinSchoolRequest): self
    {
        if ($this->joinSchoolRequests->contains($joinSchoolRequest)) {
            $this->joinSchoolRequests->removeElement($joinSchoolRequest);
            // set the owning side to null (unless already changed)
            if ($joinSchoolRequest->getSchool() === $this) {
                $joinSchoolRequest->setSchool(null);
            }
        }

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }
}