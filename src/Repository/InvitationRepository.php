<?php

namespace Nurschool\Repository;

use Nurschool\Entity\Invitation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Invitation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invitation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invitation[]    findAll()
 * @method Invitation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvitationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invitation::class);
    }

    public function findByCode(string $code): ?Invitation
    {
        return $this->findOneBy([
            'selector' => $code,
            'user' => null
        ]);
    }

    public function findBySelector(string $selector): ?Invitation
    {
        $queryBuilder = $this->createQueryBuilder('i');
        return $queryBuilder
            ->where('MD5(i.code) = :selector')
            ->andWhere($queryBuilder->expr()->isNull('i.user'))
            ->getParameter('selector', $selector)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findCodeById(int $id): ?string
    {
        if (null === ($invitation = $this->find($id))) {
            return null;
        }

        return $invitation->getCode();
    }

    // /**
    //  * @return Invitation[] Returns an array of Invitation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Invitation
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
