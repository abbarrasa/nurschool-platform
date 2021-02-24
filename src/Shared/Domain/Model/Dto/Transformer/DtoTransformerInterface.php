<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Shared\Domain\Model\Dto\Transformer;


use Nurschool\Shared\Domain\Model\Dto\Exception\UnexpectedTypeException;

interface DtoTransformerInterface
{
    /**
     * @param $object
     * @return mixed
     * @throws UnexpectedTypeException
     */
    public function transformFromObject($object);

    /**
     * @param iterable $objects
     * @return iterable
     * @throws UnexpectedTypeException
     */
    public function transformFromObjects(iterable $objects): iterable;
}