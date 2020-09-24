<?php

namespace Nurschool\Repository;

use Nurschool\Entity\AdminLevel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdminLevel|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminLevel|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminLevel[]    findAll()
 * @method AdminLevel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminLevelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdminLevel::class);
    }

    public function findByLevelAndName(int $level, string $name): AdminLevel
    {
        return $this->findOneBy(['level' => $level, 'name' => $name]);
    }

    // /**
    //  * @return AdminLevel[] Returns an array of AdminLevel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdminLevel
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
