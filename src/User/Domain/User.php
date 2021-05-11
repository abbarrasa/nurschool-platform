<?php


namespace Nurschool\User\Domain;


use Nurschool\Shared\Domain\AggregateRoot;
use Nurschool\User\Domain\ValueObject\HashedPassword;
use Nurschool\User\Domain\ValueObject\UserId;

class User extends AggregateRoot
{
    private $id;

    private $email;

    private $hashedPassword;

    public function __construct(UserId $id, Email $email, HashedPassword $hashedPassword)
    {
        $this->id = $id;
        $this->email = $email;
        $this->hashedPassword = $hashedPassword;
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function hashedPassword(): HashedPassword
    {
        return $this->hashedPassword;
    }
}