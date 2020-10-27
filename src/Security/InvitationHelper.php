<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Security;


use Nurschool\Entity\Invitation;
use Nurschool\Generator\InvitationTokenGenerator;
use Nurschool\Generator\TokenGeneratorInterface;
use Nurschool\Model\TokenComponents;
use Nurschool\Repository\InvitationRepository;
use Nurschool\Security\Exception\ExpiredInvitationTokenException;
use Nurschool\Security\Exception\InvalidInvitationTokenException;

class InvitationHelper
{
    /**
     * How long a token is valid in seconds
     * @var int
     */
    protected $tokenLifetime;

    /** @var InvitationTokenGenerator */
    protected $tokenGenerator;

    /** @var InvitationRepository */
    protected $repository;

    /**
     * InvitationHelper constructor.
     * @param InvitationTokenGenerator $tokenGenerator
     * @param InvitationRepository $repository
     */
    public function __construct(InvitationTokenGenerator $tokenGenerator, InvitationRepository $repository)
    {
        $this->tokenGenerator = $tokenGenerator;
        $this->repository = $repository;
    }

    /**
     * Set token lifetime in seconds
     * @param int $tokenLifetime
     */
    public function setTokenLifetime(int $tokenLifetime): void
    {
        $this->tokenLifetime = $tokenLifetime;
    }

    /**
     * @param Invitation $invitation
     * @return TokenComponents
     * @throws \Exception
     */
    public function generateInvitationToken(Invitation $invitation): TokenComponents
    {
        if ($this->tokenLifetime) {
            $expiresAt = clone $invitation->getRequestedAt();
            $expiresAt->modify(\sprintf('+%d seconds', $this->tokenLifetime));
        } else {
            $expiresAt = null;
        }

        //Set the expiration date of invitation
        $invitation->setExpiresAt($expiresAt);

        return $this->tokenGenerator->createToken($invitation);
    }

    /**
     * Validates a token and returns associated invitation.
     * @param string $token
     * @return Invitation
     * @throws ExpiredInvitationTokenException
     * @throws InvalidInvitationTokenException
     */
    public function validateTokenAndFetchInvitation(string $publicToken): Invitation
    {
        $selector = \substr($publicToken, 0, InvitationTokenGenerator::SELECTOR_LENGTH);
        if (null === ($invitation = $this->repository->findBySelector($selector))) {
            throw new InvalidInvitationTokenException('The invitation link is invalid.');
        }

        if ($invitation->isExpired()) {
            throw new ExpiredInvitationTokenException('The link in your invitation email is expired.');
        }

        $verifier = \substr($publicToken, InvitationTokenGenerator::SELECTOR_LENGTH, InvitationTokenGenerator::VERIFIER_LENGTH);
        $verifierToken = $this->tokenGenerator->createToken($invitation, $verifier);
        $token = \substr($publicToken, InvitationTokenGenerator::SELECTOR_LENGTH + InvitationTokenGenerator::VERIFIER_LENGTH);

        if (false === \hash_equals($token, $verifierToken->getToken())) {
            throw new InvalidInvitationTokenException('The invitation link has an invalid token.');
        }

        return $invitation;
    }
}