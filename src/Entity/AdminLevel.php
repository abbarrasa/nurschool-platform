<?php

namespace Nurschool\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Nurschool\Repository\AdminLevelRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdminLevelRepository::class)
 * @ORM\Table(name="nurschool_admin_level")
 */
class AdminLevel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $level;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=AdminLevel::class, inversedBy="children")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=AdminLevel::class, mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity=Locality::class, mappedBy="adminLevel", orphanRemoval=true)
     */
    private $localities;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class, inversedBy="adminLevels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

    /**
     * @param int         $level
     * @param string      $name
     * @param string|null $code
     */
    public function __construct(int $level, string $name, string $code = null)
    {
        $this->level = $level;
        $this->name = $name;
        $this->code = $code;
        $this->children = new ArrayCollection();
        $this->localities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
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

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Locality[]
     */
    public function getLocalities(): Collection
    {
        if ($this->localities->isEmpty()) {
            if (!$this->children->isEmpty()) {
                /** @var AdminLevel $child */
                foreach($this->children as $child) {
                    $localities = $child->getLocalities();
                    if (!$localities->isEmpty()) {
                        return $localities;
                    }
                }
            }

            if (null !== $this->parent) {
                return $this->getParent()->getLocalities();
            }
        }

        return $this->localities;
    }

    public function addLocality(Locality $locality): self
    {
        if (!$this->localities->contains($locality)) {
            $this->localities[] = $locality;
            $locality->setAdminLevel($this);
        }

        return $this;
    }

    public function removeLocality(Locality $locality): self
    {
        if ($this->localities->contains($locality)) {
            $this->localities->removeElement($locality);
            // set the owning side to null (unless already changed)
            if ($locality->getAdminLevel() === $this) {
                $locality->setAdminLevel(null);
            }
        }

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }
}