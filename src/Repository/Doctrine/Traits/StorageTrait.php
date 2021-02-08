<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Repository\Doctrine\Traits;


use Doctrine\ORM\EntityManagerInterface;

trait StorageTrait
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @required
     * @param EntityManagerInterface $entityManager
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save($object, bool $andFlush = true)
    {
        $this->entityManager->persist($object);
        if ($andFlush) {
            $this->entityManager->flush();
        }
    }

    public function remove($object, bool $andFlush = true)
    {
        $this->entityManager->remove($object);
        if ($andFlush) {
            $this->entityManager->flush();
        }
    }

}