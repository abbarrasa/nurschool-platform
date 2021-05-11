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

abstract class AbstractDoctrineRepository extends ServiceEntityRepository
{
    abstract public function createEntity(AggregateRoot $aggregateRoot): EntityInterface;

    /**
     * Store an object.
     * @param AggregateRoot $aggregateRoot
     * @param bool $andFlush
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(AggregateRoot $aggregateRoot, bool $andFlush = true)
    {
        $entity = $this->createEntity($aggregateRoot);
        $this->_em->persist($entity);
        if ($andFlush) {
            $this->_em->flush();
        }
    }

    /**
     * @param AggregateRoot $aggregateRoot
     * @param bool $andFlush
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(AggregateRoot $aggregateRoot, bool $andFlush = true)
    {
        $entity = $this->createEntity($aggregateRoot);
        $this->_em->remove($entity);
        if ($andFlush) {
            $this->_em->flush();
        }
    }
}