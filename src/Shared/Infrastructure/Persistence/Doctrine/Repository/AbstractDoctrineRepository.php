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

abstract class AbstractDoctrineRepository extends ServiceEntityRepository
{
    /**
     * Store an object.
     * @param $object
     * @param bool $andFlush
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save($object, bool $andFlush = true)
    {
        $this->_em->persist($object);
        if ($andFlush) {
            $this->_em->flush();
        }
    }

    /**
     * @param $object
     * @param bool $andFlush
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($object, bool $andFlush = true)
    {
        $this->_em->remove($object);
        if ($andFlush) {
            $this->_em->flush();
        }
    }
}