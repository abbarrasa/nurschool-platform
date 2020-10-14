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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;

trait AuthenticationSuccessTrait
{
    /** @var RouterInterface */
    private $router;

    /**
     * @required
     * @param RouterInterface $router
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getAuthenticatedResponse(SessionInterface $session, UserInterface $user, string $targetPath = null)
    {
        if ($session->has('SendConfirmationEmail')) {
            return new RedirectResponse($this->router->generate('register_done'));
        }

        if (!$user->isConfigured())  {
            if (!$this->matchWithRoute('verify_email', $targetPath)) {
                return new RedirectResponse($this->router->generate('welcome'));
            }
        }

        if ($targetPath) {
            return new RedirectResponse($targetPath);
        }
        
        return new RedirectResponse($this->router->generate('dashboard'));
    }

    /**
     * @param string $route
     * @param string|null $url
     * @return bool
     */
    private function matchWithRoute(string $route, ?string $url): bool
    {
        if ($url) {
            $parsedUrl = parse_url($url);
            $routeInfo = $this->router->match($parsedUrl['path']);

            return $routeInfo['_route'] == $route;
        }

        return false;
    }
}