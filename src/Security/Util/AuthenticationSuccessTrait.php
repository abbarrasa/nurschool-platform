<?php

/**
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Security\Util;


use Nurschool\Model\UserInterface;

trait AuthenticationSuccessTrait
{
    private $urlGenerator;

    public function getAuthenticatedResponse(Request $request, UserInterface $user, string $targetPath = null)
    {
        if ($request->attributes->get('_route') !== 'verify_email') {
            if (!$user->isVerified() || !$user->hasAnyRole())  {
                throw new \Exception('TODO: Redirect to Welcome '.__FILE__);
            }
        }
        
        
        if ($targetPath) {
            return new RedirectResponse($targetPath);
        }
        
        return new RedirectResponse($this->urlGenerator->generate('dashboard'));
    }

    /**
     * @required
     */
    public function setUrlGenerator(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }
}