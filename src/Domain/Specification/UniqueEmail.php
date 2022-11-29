<?php

namespace Nurschool\Platform\Domain\Specification;

use Nurschool\Platform\Domain\ValueObject\Email;

interface UniqueEmail
{
    public function isSatisfiedBy(Email $email): bool;
}
