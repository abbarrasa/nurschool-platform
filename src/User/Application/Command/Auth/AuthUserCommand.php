<?php


namespace Nurschool\User\Application\Command\Auth;


use Nurschool\Shared\Application\Command\Command;
use Nurschool\User\Domain\ValueObject\Auth\Credentials;

class AuthUserCommand implements Command
{
    /** @var Credentials */
    public $credentials;

    public function __construct(Credentials $credentials)
    {
        $this->credentials = $credentials;
    }

    public static function create(string $email, string $plainPassword): self
    {
        return new self(Credentials::create($email, $plainPassword));
    }
}