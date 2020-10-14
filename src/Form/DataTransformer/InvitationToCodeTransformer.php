<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Form\DataTransformer;


use Doctrine\ORM\EntityManagerInterface;
use Nurschool\Entity\Invitation;
use Nurschool\Repository\InvitationRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * Transforms an invitation to an invitation code.
 */
class InvitationToCodeTransformer implements DataTransformerInterface
{
    protected $repository;

    public function __construct(InvitationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function transform($value)
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof Invitation) {
            throw new UnexpectedTypeException($value, Invitation::class);
        }

        return $value->getCode();
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($value)
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        return $this->repository->findByCode($value);
    }
}