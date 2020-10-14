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
use Nurschool\Generator\TokenGeneratorInterface;
use Nurschool\Model\TokenComponents;
use Nurschool\Repository\InvitationRepository;
use Nurschool\Security\Exception\ExpiredInvitationTokenException;
use Nurschool\Security\Exception\InvalidInvitationTokenException;

class InvitationHelper
{
    /** @var string */
    protected $signingKey;

    /**
     * How long a token is valid in seconds
     * @var int
     */
    protected $tokenLifetime;

    /** @var TokenGeneratorInterface */
    protected $tokenGenerator;

    /** @var InvitationRepository */
    protected $repository;

    /**
     * InvitationHelper constructor.
     * @param string $signingKey
     * @param TokenGeneratorInterface $tokenGenerator
     * @param InvitationRepository $repository
     */
    public function __construct(string $signingKey, TokenGeneratorInterface $tokenGenerator, InvitationRepository $repository)
    {
        $this->signingKey = $signingKey;
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
     * Generates a valid token for an invitation.
     * @param Invitation $invitation
     * @return TokenComponents
     */
    public function generateInvitationToken(Invitation $invitation): TokenComponents
    {
        if ($this->tokenLifetime) {
            $expiresAt = clone $invitation->getRequestedAt();
            $expiresAt->modify(\sprintf('+%d seconds', $this->tokenLifetime));
        } else {
            $expiresAt = null;
        }

        return $this->tokenGenerator->createToken($invitation->getCode(), $expiresAt);
    }

    /**
     * Validates a token and returns associated invitation.
     * @param string $token
     * @return Invitation
     * @throws ExpiredInvitationTokenException
     * @throws InvalidInvitationTokenException
     */
    public function validateTokenAndFetchInvitation(string $token): Invitation
    {
        $selector = \substr($token, 0, Invitation::$_SELECTOR_LENGTH);

        if (null === ($invitation = $this->repository->findBySelector($selector))) {
            throw new InvalidInvitationTokenException('The invitation link is invalid.');
        }

        if ($invitation->isExpired()) {
            throw new ExpiredInvitationTokenException('The link in your invitation email is expired.');
        }

        $verifierToken = $this->tokenGenerator->createToken(
            $invitation->getCode(),
            $invitation->getExpiresAt(),
            \substr($token, Invitation::$_SELECTOR_LENGTH)
        );

        if (false === \hash_equals($token, $verifierToken->getToken())) {
            throw new InvalidInvitationTokenException('The invitation link is invalid.');
        }

        return $invitation;
    }
}