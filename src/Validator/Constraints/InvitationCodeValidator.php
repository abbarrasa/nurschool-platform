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
use Nurschool\Repository\InvitationRepository;
use Nurschool\Security\InvitationHelper;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class InvitationCodeValidator extends ConstraintValidator
{
    /** @var InvitationRepository */
    private $repository;

    public function __construct(InvitationRepository $repository)
    {
        $this->repository = $repository;
    }

//    /** @var RequestStack */
//    private $requestStack;
//
//    /** @var EntityManagerInterface */
//    private $entityManager;

//    private $helper;

//    public function __construct(InvitationHelper $helper)
//    {
//        $this->helper = $helper;
//    }


//    /**
//     * InvitationCodeValidator constructor.
//     * @param RequestStack $requestStack
//     * @param EntityManagerInterface $entityManager
//     */

//    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
//    {
//        $this->requestStack = $requestStack;
//        $this->entityManager = $entityManager;
//    }


    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof InvitationCode) {
            throw new UnexpectedTypeException($constraint, InvitationCode::class);
        }

        if (!$value instanceof Invitation) {
            throw new UnexpectedTypeException($value, Invitation::class);
        }

        if (empty($value)) {
            $this
                ->context
                ->buildViolation($constraint->requiredMessage)
                ->atPath('code')
                ->addViolation()
            ;
        }

        //Check invitation code from database
        if ($value->getCode() != $this->repository->findCodeById($value->getId())) {
            $this
                ->context
                ->buildViolation($constraint->invalidMessage)
                ->atPath('code')
                ->addViolation()
            ;
        }
    }
}