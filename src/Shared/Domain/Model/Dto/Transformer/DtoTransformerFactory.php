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


use Nurschool\Forum\Domain\Model\Dto\DiscussionDto;
use Nurschool\Forum\Domain\Model\Dto\Transform\DiscussionDtoTransform;
use Nurschool\Core\Domain\Model\Dto\Transformer\UserDtoTransformer;
use Nurschool\Core\Domain\Model\Dto\UserDto;

class DtoTransformerFactory implements DtoTransformerFactoryInterface
{
    public function createDtoTranformer(string $dtoClassName): DtoTransformerInterface
    {
        switch($dtoClassName) {
            case UserDto::class:
                return new UserDtoTransformer();
            case DiscussionDto::class:
                return new DiscussionDtoTransform();
        }

        throw new \LogicException(sprintf('Unable to find a transformer for "%s" provided'), $dtoClassName);
    }
}