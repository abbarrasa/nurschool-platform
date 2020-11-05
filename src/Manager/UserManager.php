<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Manager;


use Nurschool\Entity\Invitation;
use Nurschool\Model\UserInterface;
use Nurschool\Model\UserRepositoryInterface;
use Nurschool\Repository\UserRepository;

class UserManager
{
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Creates an user object.
     *
     * @return UserInterface
     */
    public function createUser(): UserInterface
    {
        return $this->repository->createUser();
    }

    /**
     * Creates an user object with an invitation.
     *
     * @param Invitation $invitation
     * @return UserInterface
     */
    public function createUserFromInvitation(Invitation $invitation): UserInterface
    {
        $user = $this->createUser();
        $user
            ->setInvitation($invitation)
            ->setEmail($invitation->getEmail())
            ->setFirstname($invitation->getFirstname())
            ->setLastname($invitation->getLastname())
            ->setRoles($invitation->getRoles())
            ->setIsVerified(true);
        ;

        foreach($invitation->getSchools() as $school) {
            foreach($invitation->getRoles() as $role) {
                switch($role){
                    case 'ROLE_ADMIN':
                        $school->addAdmin($user);
                        break;

                    case 'ROLE_NURSE':
                        $school->addNurse($user);
                }
            }

            $user->addSchool($school);
        }

        return $user;
    }

    /**
     * Finds an user by its email.
     *
     * @param string $email
     * @return UserInterface|null
     */
    public function findByEmail(string $email): ?UserInterface
    {
        return $this->repository->findOneBy(['email' => $email]);
    }

    /**
     * Finds an user by its Google ID.
     *
     * @param string $googleUid
     * @return UserInterface|null
     */
    public function findByGoogleUid(string $googleUid): ?UserInterface
    {
        return $this->repository->findOneBy(['googleUid' => $googleUid]);
    }

    /**
     * Finds an user by its Facebook ID.
     *
     * @param string $facebookUid
     * @return UserInterface|null
     */
    public function findByFacebookUid(string $facebookUid): ?UserInterface
    {
        return $this->repository->findOneBy(['facebookUid' => $facebookUid]);
    }

    /**
     * Upgrades an user password.
     *
     * @param UserInterface $user
     * @param string $newEncodedPassword
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword)
    {
        $this->repository->upgradePassword($user, $newEncodedPassword);
    }

    /**
     * Stores an user object.
     *
     * @param UserInterface $user
     * @param bool $andFlush
     */
    public function save(UserInterface $user, bool $andFlush = true): void
    {
        $this->repository->save($user, $andFlush);
    }
}