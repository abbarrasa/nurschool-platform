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

namespace Nurschool\Platform\Domain\Repository;

use Nurschool\Common\Domain\Repository\QueryRepository;
use Nurschool\Common\Domain\ValueObject\Uuid;
use Nurschool\Platform\Domain\User;
use Nurschool\Platform\Domain\ValueObject\Email;
use Nurschool\Platform\Domain\ValueObject\GoogleId;

interface UserQueryRepository extends QueryRepository
{
    public function findByUuidOrFail(Uuid $uuid): User;
    
    public function existsWithEmail(Email $email): bool;

    public function findByEmailOrFail(Email $email): User;

    public function findByGoogleIdOrFail(GoogleId $googleId): User;
}
