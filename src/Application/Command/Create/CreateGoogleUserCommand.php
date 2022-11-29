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

namespace Nurschool\Platform\Application\Command\Create;

class CreateGoogleUserCommand extends CreateUserCommand
{
    public string $googleId;
    public string $image;

    public function __construct(
        string $email,
        string $googleId,
        string $firstname,
        string $lastname,
        string $image
    ) {
        $this->googleId = $googleId;
        $this->image = $image;

        parent::__construct($email, $firstname, $lastname);
    }
}
