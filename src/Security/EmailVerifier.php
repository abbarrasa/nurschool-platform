<?php

namespace Nurschool\Security;

use Doctrine\ORM\EntityManagerInterface;
use Nurschool\Mailer\MailerInterface;
use Nurschool\Model\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\Model\VerifyEmailSignatureComponents;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    private $verifyEmailHelper;
    private $entityManager;

    public function __construct(VerifyEmailHelperInterface $helper, EntityManagerInterface $manager)
    {
        $this->verifyEmailHelper = $helper;
        $this->entityManager = $manager;
    }

    /**
     * @param string $verifyEmailRouteName
     * @param UserInterface $user
     * @return VerifyEmailSignatureComponents
     */
    public function generateSignatureConfirmation(string $verifyEmailRouteName, UserInterface $user): VerifyEmailSignatureComponents
    {
        return $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            $user->getId(),
            $user->getEmail()
        );
    }

    /**
     * @param Request $request
     * @param UserInterface $user
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(Request $request, UserInterface $user): void
    {
        $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());

        $user->setIsVerified(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
