<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Nurschool\User\Domain\Model;


use Symfony\Component\Uid\Ulid;

interface UserInterface
{
    public function getId(): ?Ulid;

    public function getEmail(): ?string;

    public function setEmail(string $email);

    public function getFirstname(): ?string;

    public function setFirstname(?string $firstname);

    public function getLastname();

    public function setLastname(?string $lastname);

    public function getPassword();

    public function setPassword(?string $password);

    public function getRoles(): ?array;

    public function setRoles(array $roles);

    public function isEnabled(): bool;

    public function setEnabled(bool $enabled);

    public function getUsername(): string;
}