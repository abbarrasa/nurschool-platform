<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Repository\Doctrine;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class DoctrineRepository extends ServiceEntityRepository
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
        $this->getEntityManager()->persist($object);
        if ($andFlush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Remove an object.
     * @param $object
     * @param bool $andFlush
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove($object, bool $andFlush = true)
    {
        $this->getEntityManager()->remove($object);
        if ($andFlush) {
            $this->getEntityManager()->flush();
        }
    }
}