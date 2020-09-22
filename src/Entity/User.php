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
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Nurschool\Model\UserInterface;
use Nurschool\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="nurschool_user")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @Vich\Uploadable
 */
class User implements UserInterface
{
    /**
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private $googleUid;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Version
     */
    private $version;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled = true;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\ManyToMany(targetEntity=School::class, mappedBy="users")
     */
    private $schools;

    /**
     * @ORM\OneToMany(targetEntity=JoinSchoolRequest::class, mappedBy="applicant", orphanRemoval=true)
     */
    private $joinSchoolRequests;

    /**
     * @Vich\UploadableField(mapping="user_images", fileNameProperty="avatar")
     * @var File
     */
    private $avatarFile;

    public function __construct()
    {
        $this->schools = new ArrayCollection();
        $this->joinSchoolRequests = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getGoogleUid(): ?string
    {
        return $this->googleUid;
    }

    public function setGoogleUid(?string $googleUid): self
    {
        $this->googleUid = $googleUid;

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

    public function getFullName(): ?string
    {
        return "{$this->firstname} {$this->lastname}";
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function hasRole(string $role): bool
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    public function hasAnyRole(): bool
    {
        return $this->hasRole('ROLE_ADMIN') || $this->hasRole('ROLE_NURSE');
    }

    /**
     * @see \Symfony\Component\Security\Core\User\UserInterface
     */
    public function getPassword()
    {
        return (string) $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

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

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

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
            $school->addUser($this);
        }

        return $this;
    }

    public function removeSchool(School $school): self
    {
        if ($this->schools->contains($school)) {
            $this->schools->removeElement($school);
            $school->removeUser($this);
        }

        return $this;
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
            $joinSchoolRequest->setApplicant($this);
        }

        return $this;
    }

    public function removeJoinSchoolRequest(JoinSchoolRequest $joinSchoolRequest): self
    {
        if ($this->joinSchoolRequests->contains($joinSchoolRequest)) {
            $this->joinSchoolRequests->removeElement($joinSchoolRequest);
            // set the owning side to null (unless already changed)
            if ($joinSchoolRequest->getApplicant() === $this) {
                $joinSchoolRequest->setApplicant(null);
            }
        }

        return $this;
    }

    /**
     * @return File
     */
    public function getAvatarFile(): ?File
    {
        return $this->avatarFile;
    }

    /**
     * @param File|null $avatarFile
     */
    public function setAvatarFile(File $avatarFile): void
    {
        $this->avatarFile = $avatarFile;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($avatarFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function cleanAvatarFile(): void
    {
        $this->avatarFile = null;
    }

    public function isConfigured(): bool
    {
        return
            $this->isEnabled() &&
            $this->isVerified() &&
            $this->hasAnyRole() &&
            (!$this->getSchools()->isEmpty() || !$this->getJoinSchoolRequests()->isEmpty())
        ;
    }
}