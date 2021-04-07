<?php


namespace Nurschool\User\Application\Command\SignIn;


use Nurschool\Shared\Application\Command\CommandInterface;

final class SignInCommand implements CommandInterface
{
    public Email $email;

    public $plainPassword;

    /**
     * @throws AssertionFailedException
     */
    public function __construct(string $email, string $plainPassword)
    {
        $this->email = Email::fromString($email);
        $this->plainPassword = $plainPassword;
    }
}