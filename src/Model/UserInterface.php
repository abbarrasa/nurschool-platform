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


interface UserInterface extends \Symfony\Component\Security\Core\User\UserInterface
{
    /**
     * Get identifier.
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Get email
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * Set email
     * @param string $email
     */
    public function setEmail(string $email);

    /**
     * Get Google user ID
     * @return string|null
     */
    public function getGoogleUid(): ?string;

    /**
     * Set Google user ID
     * @param string|null $googleUid
     */
    public function setGoogleUid(?string $googleUid);

    /**
     * Set password
     * @param string $password
     */
    public function setPassword(string $password);

    /**
     * Get firstname
     * @return string|null
     */
    public function getFirstname(): ?string;

    /**
     * Set firstname
     * @param string $firstname
     */
    public function setFirstname(string $firstname);

    /**
     * Get lastname
     * @return string|null
     */
    public function getLastname(): ?string;

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname);

    /**
     * Get fullname (firstname and lastname)
     */
    public function getFullName();

    /**
     * Check if user has the role
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool;

    /**
     * Check if user has any valid role
     * @return bool
     */
    public function hasAnyRole(): bool;

    /**
     * Check if user is verified
     * @return bool
     */
    public function isVerified(): bool;

    /**
     * Set verified flag
     * @param bool $isVerified
     */
    public function setIsVerified(bool $isVerified);

    /**
     * Check if user is enabled
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Set enabled flag
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled);

    /**
     * Get user's last login
     * @return \DateTimeInterface|null
     */
    public function getLastLogin(): ?\DateTimeInterface;

    /**
     * Set user's last login
     * @param \DateTimeInterface|null $datetime
     */
    public function setLastLogin(?\DateTimeInterface $datetime);

    /**
     * Get file name of user avatar
     * @return string|null
     */
    public function getAvatar(): ?string;

    /**
     * Set file name of user avatar
     * @param string|null $avatar
     * @return mixed
     */
    public function setAvatar(?string $avatar);

    /**
     * Check if user is configured and ready to use the application
     * @return bool
     */
    public function isConfigured(): bool;
}