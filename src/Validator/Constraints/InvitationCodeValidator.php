<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Validator\Constraints;


use Doctrine\ORM\EntityManagerInterface;
use Nurschool\Entity\Invitation;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class InvitationCodeValidator extends ConstraintValidator
{
    /** @var RequestStack */
    private $requestStack;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * InvitationCodeValidator constructor.
     * @param RequestStack $requestStack
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof InvitationCode) {
            throw new UnexpectedTypeException($constraint, InvitationCode::class);
        }

        if (empty($value)) {
            $this
                ->context
                ->buildViolation($constraint->requiredMessage)
                ->addViolation()
            ;
        }

        $code = $value instanceof Invitation ? $value->getCode() : $value;
        if ($code != $this->requestStack->getCurrentRequest()->attributes->get($constraint->parameter)) {
            $this
                ->context
                ->buildViolation($constraint->invalidMessage)
                ->addViolation()
            ;
        }

        $invitation = $this->entityManager
            ->getRepository(Invitation::class)
            ->findByCode($code)
        ;

        if (null === $invitation) {
            $this
                ->context
                ->buildViolation($constraint->invalidMessage)
                ->addViolation()
            ;
        }
    }

}