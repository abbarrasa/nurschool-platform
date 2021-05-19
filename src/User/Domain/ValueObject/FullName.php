<?php


namespace Nurschool\User\Domain\ValueObject;


class FullName
{
    /** @var string */
    private $firstname;

    /** @var string */
    private $lastname;

    /**
     * Fullname constructor.
     * @param string $firstname
     * @param string $lastname
     */
    public function __construct(string $firstname, string $lastname)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }

    public static function create(string $firstname, string $lastname): self
    {
        return new self($firstname, $lastname);
    }

    public function changeFirstname(string $firstname)
    {
        $this->firstname = $firstname;
    }

    public function changeLastname(string $lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }
}