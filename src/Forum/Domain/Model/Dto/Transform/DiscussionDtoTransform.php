<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Forum\Domain\Model\Dto\Transform;


use Nurschool\Forum\Domain\Model\DiscussionInterface;
use Nurschool\Forum\Domain\Model\Dto\DiscussionDto;
use Nurschool\Shared\Domain\Model\Dto\Exception\UnexpectedTypeException;
use Nurschool\Shared\Domain\Model\Dto\Transformer\AbstractDtoTransformer;

final class DiscussionDtoTransform extends AbstractDtoTransformer
{
    /**
     * @inheritdoc
     */
    public function transformFromObject($object): DiscussionDto
    {
        if (!$object instanceof DiscussionInterface) {
            throw new UnexpectedTypeException($object, DiscussionInterface::class);
        }

        $dto = new DiscussionDto();
        $dto->title = $object->getTitle();
        $dto->slug = $object->getSlug();
        $dto->createdAt = $object->getCreatedAt();
        $dto->updatedAt = $object->getUpdatedAt();

        return $dto;
    }
}