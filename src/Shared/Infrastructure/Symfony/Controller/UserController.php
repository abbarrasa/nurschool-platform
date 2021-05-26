<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Shared\Infrastructure\Symfony\Controller;


use Nurschool\Shared\Domain\Model\Repository\RepositoryInterface;
use Nurschool\Core\Domain\Model\Dto\UserDto;
use Nurschool\User\Domain\Model\Repository\UserRepository;

class UserController extends AbstractCrudController
{
    /** @var UserRepository */
    private $repository;

    /**
     * @required
     * @param UserRepository $repository
     */
    public function setRepository(UserRepository $repository): void
    {
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function getDtoClassName(): string
    {
        return UserDto::class;
    }

    /**
     * @inheritDoc
     */
    public function getRepository(): RepositoryInterface
    {
        return $this->repository;
    }

}