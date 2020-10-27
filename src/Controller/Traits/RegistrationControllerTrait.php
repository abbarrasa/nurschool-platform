<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Controller\Traits;


use Symfony\Component\HttpFoundation\Session\SessionInterface;

trait RegistrationControllerTrait
{
    /** @var SessionInterface */
    private $session;

    /**
     * @required
     * @param SessionInterface $session
     */
    public function setSession(SessionInterface $session)
    {
        $this->session = $session;
    }

    private function checkConfirmationEmailInSession(): bool
    {
        return $this->session->has('SendConfirmationEmail');
    }

    private function getConfirmationEmailFromSession(): ?string
    {
        return $this->session->get('SendConfirmationEmail');
    }

    private function getConfirmationTokenExpiresAtFromSession(): ?\DateTimeInterface
    {
        return $this->session->get('SendConfirmationTokenExpiresAt');
    }

    private function cleanSessionAfterConfirmation():void
    {
        $this->session->remove('SendConfirmationEmail');
        $this->session->remove('SendConfirmationTokenExpiresAt');
    }

    private function storeInvitationTokenInSession(string $token): void
    {
        $this->session->set('InvitationPublicToken', $token);
    }

    private function getInvitationTokenFromSession(): ?string
    {
        return $this->session->get('InvitationPublicToken');
    }

    private function cleanSessionAfterRegistration(): void
    {
        $this->session->remove('InvitationPublicToken');
    }
}