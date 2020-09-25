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

use Bazinga\GeocoderBundle\Mapping\Annotations as Geocoder;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Nurschool\Entity\Traits\GeocodeableEntity;
use Nurschool\Model\LocationInterface;
use Nurschool\Repository\SchoolRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=SchoolRepository::class)
 * @ORM\Table(name="nurschool_school")
 * @Vich\Uploadable
 * @Geocoder\Geocodeable
 */
class School implements LocationInterface
{
    /**
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableEntity;

    /**
     * Hook geocodeable behavior
     * updates geolocation fields
     */
    use GeocodeableEntity;

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

    /**
     * @ORM\ManyToOne(targetEntity=Locality::class, inversedBy="schools")
     * @ORM\JoinColumn(nullable=false)
     */
    private $locality;

    /**
     * @Vich\UploadableField(mapping="school_images", fileNameProperty="logo")
     * @var File
     */
    private $logoFile;

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

    /**
     * @see LocationInterface
     */
    public function getLocality(): Locality
    {
        return $this->locality;
    }

    public function setLocality(Locality $locality): self
    {
        $this->locality = $locality;

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

    /**
     * @return File|null
     */
    public function getLogoFile(): ?File
    {
        return $this->logoFile;
    }

    /**
     * @param File|null $logoFile
     */
    public function setLogoFile(File $logoFile): void
    {
        $this->logoFile = $logoFile;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($logoFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }
}