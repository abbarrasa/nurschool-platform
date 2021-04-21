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

namespace Nurschool\Forum\Domain\Model\Dto\Transform;

use Nurschool\Forum\Domain\Model\DiscussionInterface;
use Nurschool\Forum\Domain\Model\Dto\DiscussionDto;
use Nurschool\Forum\Infrastructure\Persistence\Doctrine\Entity\Discussion;
use Nurschool\Shared\Domain\Model\Dto\Exception\UnexpectedTypeException;
use Nurschool\Shared\Domain\Model\Dto\Transformer\AbstractDtoTransformer;

final class DiscussionDtoTransform extends AbstractDtoTransformer
{
    /**
     * @inheritDoc
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

    /**
     * @inheritDoc
     */
    public function transformFromDto($dto)
    {
        if (!$dto instanceof DiscussionDto) {
            throw new UnexpectedTypeException($dto, DiscussionDto::class);
        }

        $discussion = new Discussion();
        $discussion->setTitle($dto->title);
        $discussion->setSlug($dto->slug);
        $discussion->setCreatedAt($dto->createdAt);
        $discussion->setUpdatedAt($dto->updatedAt);

        return $discussion;
    }
}