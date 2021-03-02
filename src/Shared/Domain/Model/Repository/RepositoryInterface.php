<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Shared\Domain\Model\Repository;


interface RepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null);

    public function findOneBy(array $criteria, array $orderBy = null);

    public function findAll();

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    public function save($object, bool $andFlush = true);

    public function delete($object, bool $andFlush = true);
}