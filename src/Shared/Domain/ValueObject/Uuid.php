<?php


namespace Nurschool\Shared\Domain\ValueObject;


class Uuid
{
    protected $id;

    public function __construct(string $id)
    {
        $this->id;
    }

    public static function generate(): string
    {
        return BaseUuid::uuid4()->toString();
    }

    public function value(): string
    {
        return $this->id;
    }

    private function isValid(string $id): bool
    {
        return BaseUuid::isValid($id);
    }

    public function __toString(): string
    {
        return $this->value();
    }
}