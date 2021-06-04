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

namespace Nurschool\User\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Nurschool\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;
use Nurschool\User\Domain\Model\Repository\UserRepository;
use Nurschool\User\Domain\User as UserDomain;
use Nurschool\User\Domain\ValueObject\Credentials;
use Nurschool\User\Domain\ValueObject\Email;
use Nurschool\User\Infrastructure\Persistence\Doctrine\Entity\User;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class UserDoctrineRepository extends DoctrineRepository implements UserRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByEmail(Email $email): UserDomain
    {
        return $this->findOneBy(['email.value' => $email->toString()]);
    }

    /**
     * @param UserDomain $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateLastLogin(UserDomain $user)
    {
        $user->updateLastLogin();
        $this->save($user);
    }



    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
