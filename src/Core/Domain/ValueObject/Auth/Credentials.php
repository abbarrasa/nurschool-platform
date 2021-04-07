<?php


namespace Nurschool\User\Domain\ValueObject\Auth;


class Credentials
{
    public Email $email;

    public $password;

    public function __construct(Email $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

}