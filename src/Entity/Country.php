<?php

namespace Nurschool\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Nurschool\Repository\CountryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CountryRepository::class)
 * @ORM\Table(name="nurschool_country")
 */
class Country
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity=AdminLevel::class, mappedBy="country", orphanRemoval=true)
     */
    private $adminLevels;

    public function __construct(string $name, string $code = null)
    {
        $this->name = $name;
        $this->code = $code;
        $this->adminLevels = new ArrayCollection();
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection|AdminLevel[]
     */
    public function getAdminLevels(): Collection
    {
        return $this->adminLevels;
    }

    public function addAdminLevel(AdminLevel $adminLevel): self
    {
        if (!$this->adminLevels->contains($adminLevel)) {
            $this->adminLevels[] = $adminLevel;
            $adminLevel->setCountry($this);
        }

        return $this;
    }

    public function removeAdminLevel(AdminLevel $adminLevel): self
    {
        if ($this->adminLevels->contains($adminLevel)) {
            $this->adminLevels->removeElement($adminLevel);
            // set the owning side to null (unless already changed)
            if ($adminLevel->getCountry() === $this) {
                $adminLevel->setCountry(null);
            }
        }

        return $this;
    }

    public function filterAdminLevels(int $level): Collection
    {
        return $this->getAdminLevels()->filter(function($adminLevel) use($level) { return $level == $adminLevel->getLevel(); });
    }
}
