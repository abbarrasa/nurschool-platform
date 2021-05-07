<?php


namespace Nurschool\User\Domain\ValueObject;


final class Credentials
{
    /** @var Email */
    private $email;

    /** @var HashedPassword */
    private $password;

    public function __construct(Email $email, HashedPassword $hashedPassword)
    {
        $this->email = $email;
        $this->password = $hashedPassword;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return HashedPassword
     */
    public function getPassword(): HashedPassword
    {
        return $this->password;
    }
}