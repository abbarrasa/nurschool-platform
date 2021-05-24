<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Nurschool\Shared\Infrastructure\Symfony\Security;

use Doctrine\ORM\EntityManagerInterface;
use Nurschool\Shared\Domain\Service\Email\MailerInterface;
use Nurschool\Core\Domain\Model\UserInterface;
use Nurschool\User\Domain\Model\Repository\UserRepositoryInterface;
use Nurschool\User\Domain\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    private $verifyEmailHelper;
    private $mailer;
    private $userRepository;

    public function __construct(
        VerifyEmailHelperInterface $helper,
        MailerInterface $mailer,
        UserRepositoryInterface $userRepository
    ) {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }

    public function sendSignedUrl(string $routeName, User $user): void
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $routeName,
            $user->id()->toString(),
            $user->email()->toString(),
            ['id' => $user->id()->toString()]
        );

        $signedUrl = $signatureComponents->getSignedUrl();
        $expiresAt = $signatureComponents->getExpiresAt();
        $this->mailer->sendConfirmationEmail($user, $signedUrl, $expiresAt);

//        $context = $email->getContext();
//        $context['signedUrl'] = $signatureComponents->getSignedUrl();
//        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
//        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();
//
//        $email->context($context);
//
//        $this->mailer->send($email);
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleSignedUrl(Request $request): void
    {
        $id = $request->get('id');

        // Verify the user id exists and is not null
        if (null === $id) {
            throw new \Exception();
        }

        /** @var User $user */
        $user = $this->userRepository->find($id);

        // Ensure the user exists in persistence
        if (null === $user) {
            throw new \Exception();
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        $this->verifyEmailHelper->validateEmailConfirmation(
            $request->getUri(),
            $user->id()->toString(),
            $user->email()->toString()
        );

        $user->enable();
        $this->userRepository->save($user);
    }
}