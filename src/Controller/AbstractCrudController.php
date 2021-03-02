<?php


namespace Nurschool\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractCrudController
{
    private $serializer;
    private $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * Returns a DTO type.
     *
     * Used to create a DTO object from the request content.
     *
     * @return string
     */
    abstract public function getDtoClassName(): string;

    public function create(Request $request): JsonResponse
    {
        $requestDto = $this->serializer->deserialize(
            $request->getContent(),
            $this->getDtoClassName(),
            'json'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {

    }

    public function delete(Request $request, int $id): JsonResponse
    {

    }

    public function get(Request $request, int $id): JsonResponse
    {

    }

    public function list(Request $request): JsonResponse
    {

    }
}