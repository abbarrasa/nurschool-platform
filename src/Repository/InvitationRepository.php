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

    /**
     * @param string $code
     * @return Invitation|null
     */
    public function findByCode(string $code): ?Invitation
    {
        return $this->findOneBy([
            'code' => $code,
            'user' => null
        ]);
    }

    /**
     * @param string $selector
     * @return Invitation|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findBySelector(string $selector): ?Invitation
    {
        $queryBuilder = $this->createQueryBuilder('i');
        return $queryBuilder
            ->where('MD5(i.code) = :selector')
            ->setParameter('selector', $selector)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param int $id
     * @return string|null
     */
    public function findCodeById(int $id): ?string
    {
        if (null === ($invitation = $this->find($id))) {
            return null;
        }

        return $invitation->getCode();
    }
}
