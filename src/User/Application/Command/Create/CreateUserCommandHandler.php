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

namespace Nurschool\User\Application\Command\Create;

use Nurschool\Shared\Application\Command\CommandHandler;
use Nurschool\User\Domain\ValueObject\Credentials;

final class CreateUserCommandHandler implements CommandHandler
{
    /** @var UserCreator */
    private $creator;

    public function __construct(UserCreator $creator)
    {
        $this->creator = $creator;
    }

    public function __invoke(CreateUserCommand $command): void
    {
        $email = $command->getEmail();
        $hashedPassword = $command->getHashedPassword();

        $this->creator->__invoke($email, $hashedPassword);

        //https://www.acceseo.com/que-es-symfony-messenger-y-como-podemos-utilizarlo-en-nuestros-proyectos.html
//        sleep(30);
//        $log = sprintf(
//            "%s :: Usuario creado: %s - %s.\n",
//            (new \DateTime())->format('d/m/Y H:i:s'),
//            $command->getEmail()->toString(),
//            $command->getHashedPassword()->toString()
//        );
//        file_put_contents('/home/abuitrago/Proyectos/nurschool/public/create_user.txt', $log,FILE_APPEND | LOCK_EX);
    }
}