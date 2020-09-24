<?php

namespace Nurschool\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Nurschool\Repository\LocalityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LocalityRepository::class)
 * @ORM\Table(name="nurschool_locality")
 */
class Locality
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=AdminLevel::class, inversedBy="localities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $adminLevel;

    /**
     * @ORM\OneToMany(targetEntity=School::class, mappedBy="locality", orphanRemoval=true)
     */
    private $schools;

    public function __construct()
    {
        $this->schools = new ArrayCollection();
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

    public function getAdminLevel(): ?AdminLevel
    {
        return $this->adminLevel;
    }

    public function setAdminLevel(?AdminLevel $adminLevel): self
    {
        $this->adminLevel = $adminLevel;

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
            $school->setLocality($this);
        }

        return $this;
    }

    public function removeSchool(School $school): self
    {
        if ($this->schools->contains($school)) {
            $this->schools->removeElement($school);
            // set the owning side to null (unless already changed)
            if ($school->getLocality() === $this) {
                $school->setLocality(null);
            }
        }

        return $this;
    }
}