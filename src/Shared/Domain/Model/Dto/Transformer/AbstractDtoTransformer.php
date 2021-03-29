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


abstract class AbstractDtoTransformer implements DtoTransformerInterface
{
    /**
     * @inheritDoc
     */
    public function transformFromObjects(iterable $objects): iterable
    {
        $dto = [];

        foreach ($objects as $object) {
            $dto[] = $this->transformFromObject($object);
        }

        return $dto;
    }

    /**
     * @inheritDoc
     */
    public function transformFromDtos(iterable $dtos): iterable
    {
        $objects = [];

        foreach ($dtos as $dto) {
            $objects[] = $this->transformFromDto($dto);
        }

        return $objects;
    }
}