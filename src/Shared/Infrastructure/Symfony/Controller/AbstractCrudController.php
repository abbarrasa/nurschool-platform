<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Shared\Infrastructure\Symfony\Controller;


use Nurschool\Shared\Domain\Model\Dto\Transformer\DtoTransformerFactoryInterface;
use Nurschool\Shared\Domain\Model\Repository\RepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractCrudController extends AbstractController
{
    protected $serializer;
    protected $validator;
    protected $dtoTransformerFactory;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        DtoTransformerFactoryInterface $dtoTransfomerFactory
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->dtoTransformerFactory = $dtoTransfomerFactory;
    }

    /**
     * Returns a DTO type.
     *
     * Used to create a DTO object from the request content.
     *
     * @return string
     */
    abstract public function getDtoClassName(): string;

    abstract public function getRepository(): RepositoryInterface;

    public function create(Request $request): JsonResponse
    {
        $requestDto = $this->serializer->deserialize(
            $request->getContent(),
            $this->getDtoClassName(),
            'json'
        );

        $errors = $this->validator->validate($requestDto, null, ['OpCreate']);
        if (\count($errors) > 0) {
            throw new UnprocessableEntityHttpException($errors);
        }

        $dtoTransformer = $this->dtoTransformerFactory->createDtoTranformer($this->getDtoClassName());
        $entity = $dtoTransformer->transformFromDto($requestDto);
        $this->getRepository()->save($entity);

        $responseDto = $dtoTransformer->transformFromObject($entity);

        return new JsonResponse(
            $this->serializer->serialize(
                $responseDto,
                'json'
            ),
            Response::HTTP_CREATED,
            [],
            true
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {

    }

    public function delete(Request $request, int $id): JsonResponse
    {
        $entity = $this->getRepository()->find($id);
        if (null === $entity) {
            throw new NotFoundHttpException('Resource not found.');
        }

        $this->getRepository()->delete($entity);

        return new JsonResponse(
            null,
            Response::HTTP_NO_CONTENT
        );
    }

//    public function get(Request $request, int $id): JsonResponse
//    {
//        $entity = $this->getRepository()->find($id);
//        if (null === $entity) {
//            throw new NotFoundHttpException('Resource not found.');
//        }
//
//        $entity = $this->getRepository()->find($id);
//        $responseDto = $this->dtoTransformerFactory
//            ->createDtoTranformer($this->getDtoClassName())
//            ->transformFromObject($entity)
//        ;
//
//        return new JsonResponse(
//            $this->serializer->serialize(
//                $responseDto,
//                'json'
//            ),
//            Response::HTTP_OK,
//            [],
//            true
//        );
//    }

    public function list(Request $request): JsonResponse
    {
    }
}