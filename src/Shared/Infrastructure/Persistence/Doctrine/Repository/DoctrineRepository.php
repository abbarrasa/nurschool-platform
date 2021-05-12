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

namespace Nurschool\Shared\Infrastructure\Persistence\Doctrine\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Nurschool\Shared\Domain\AggregateRoot;

abstract class DoctrineRepository extends ServiceEntityRepository
{
    /**
     * Store an entity.
     * @param AggregateRoot $entity
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(AggregateRoot $entity)
    {
        $this->_em->persist($entity);
        $this->_em->flush($entity);
    }

    /**
     * Delete an entity
     * @param AggregateRoot $entity
     * @param bool $andFlush
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(AggregateRoot $entity)
    {
        $this->_em->remove($entity);
        $this->_em->flush($entity);
    }
}