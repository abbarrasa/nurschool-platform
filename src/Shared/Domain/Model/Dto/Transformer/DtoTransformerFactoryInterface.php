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


interface DtoTransformerFactoryInterface
{
    public function createDtoTranformer(string $dtoClassName): DtoTransformerInterface;
}