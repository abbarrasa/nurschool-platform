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

    public function createUser(): UserInterface
    {
        return $this->repository->createUser();
    }

    public function createUserFromInvitation(Invitation $invitation): UserInterface
    {
        $user = $this->createUser();
        $user
            ->setInvitation($invitation)
            ->setEmail($invitation->getEmail())
            ->setFirstname($invitation->getFirstname())
            ->setLastname($invitation->getLastname())
            ->setRoles($invitation->getRoles())
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

    public function findByEmail(string $email): ?UserInterface
    {
        return $this->repository->findOneBy(['email' => $email]);
    }

    public function findByGoogleUid(string $googleUid): ?UserInterface
    {
        return $this->repository->findOneBy(['googleUid' => $googleUid]);
    }

    public function findByFacebookUid(string $facebookUid): ?UserInterface
    {
        return $this->repository->findOneBy(['facebookUid' => $facebookUid]);
    }

    /**
     * @param UserInterface $user
     * @param string $newEncodedPassword
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword)
    {
        $this->repository->upgradePassword($user, $newEncodedPassword);
    }


    public function save(UserInterface $user, bool $andFlush = true): void
    {
        $this->repository->save($user, $andFlush);
    }
}