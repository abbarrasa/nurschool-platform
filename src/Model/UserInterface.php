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
    public function getId(): ?int;

    public function getEmail(): ?string;

    public function setEmail(string $email);

    public function getFirstname(): ?string;

    public function setFirstname(string $firstname);

    public function getLastname(): ?string;

    public function setLastname(string $lastname);

    public function getFullName();

    public function getPassword(): string;

    public function setPassword(string $password);
    
    public function hasRole(string $role): bool;
    
    public function hasAnyRole(): bool;

    public function isVerified(): bool;

    public function setIsVerified(bool $isVerified);

    public function isEnabled(): ?bool;

    public function setEnabled(bool $enabled);

    public function getLastLogin(): \DateTimeInterface;

    public function setLastLogin(\DateTimeInterface $datetime);
}