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

namespace Nurschool\Platform\Application\Command\User\ActivateAccount;

use Nurschool\Common\Application\Command\CommandHandler;
use Nurschool\Common\Application\Url\Exception\InvalidSignature;
use Nurschool\Common\Application\Url\SignService;
use Nurschool\Common\Application\Url\UrlUtils;
use Nurschool\Common\Domain\ValueObject\Uuid;
use Nurschool\Platform\Domain\Repository\UserQueryRepository;
use Nurschool\Platform\Domain\Repository\UserStoreRepository;
use Nurschool\Platform\Domain\User;
use Nurschool\Platform\Domain\ValueObject\Avatar;
use Nurschool\Platform\Domain\ValueObject\Email;
use Nurschool\Platform\Domain\ValueObject\FullName;
use Nurschool\Platform\Domain\ValueObject\GoogleId;

final class ActivateAccountCommandHandler implements CommandHandler
{
    private UserQueryRepository $userQueryRepository;
    private UserStoreRepository $userStoreRepository;
    private SignService $signService;

    public function __construct(
        UserQueryRepository $userQueryRepository,
        UserStoreRepository $userStoreRepository,
        SignService $signService
    ) {
        $this->userQueryRepository = $userQueryRepository;
        $this->userStoreRepository = $userStoreRepository;
        $this->signService = $signService;
    }

    public function __invoke(ActivateAccountCommand $command): void
    {
        $url = $command->url;
        $uuid = new Uuid($command->id);
        $user = $this->userQueryRepository->findByUuidOrFail($uuid);

        if (!$user->isEnabled()) {
            $tokenParams = [(string) $user->id(), (string) $user->email()];
            $this->signService->validateSignedUrl($url, $tokenParams);
            $user->enable();
            $this->userStoreRepository->save($user);    
        }
    }
}
