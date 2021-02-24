<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\User\Domain\Model\Dto\Transformer;


use Nurschool\Shared\Domain\Model\Dto\Exception\UnexpectedTypeException;
use Nurschool\Shared\Domain\Model\Dto\Transformer\AbstractDtoTransformer;
use Nurschool\User\Domain\Model\Dto\UserDto;
use Nurschool\User\Domain\Model\UserInterface;

final class UserDtoTransformer extends AbstractDtoTransformer
{
    public function transformFromObject($object): UserDto
    {
        if (!$object instanceof UserInterface) {
            throw new UnexpectedTypeException($object, UserInterface::class);
        }

        $dto = new UserDto();
        $dto->email = $object->getEmail();
        $dto->firstname = $object->getFirstname();
        $dto->lastname = $object->getLastname();
        $dto->password = $object->getPassword();
        $dto->roles = $object->getRoles();

        return $dto;
    }
}