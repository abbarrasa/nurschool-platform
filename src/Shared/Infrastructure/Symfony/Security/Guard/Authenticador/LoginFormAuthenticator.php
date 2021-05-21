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

namespace Nurschool\Shared\Infrastructure\Symfony\Security\Guard\Authenticador;

use Nurschool\User\Application\Command\Auth\UserAuthenticator;
use Nurschool\User\Domain\ValueObject\Auth\Credentials;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

final class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    private const LOGIN_ROUTE = 'login';

    private const SUCCESS_REDIRECT = 'dashboard';

    /** @var UrlGeneratorInterface */
    private $router;

    /** @var CsrfTokenManagerInterface */
    private $tokenManager;

    /** @var UserAuthenticator */
    private $authenticator;


    public function __construct(
        UrlGeneratorInterface $router,
        CsrfTokenManagerInterface $tokenManager,
        UserAuthenticator $authenticator
    ) {
        $this->router = $router;
        $this->tokenManager = $tokenManager;
        $this->authenticator = $authenticator;
    }

    public function supports(Request $request): bool
    {
        return $request->getPathInfo() === $this->router->generate(self::LOGIN_ROUTE) &&
            $request->isMethod('POST')
        ;
    }

    public function getCredentials(Request $request): array
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->tokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $email = $credentials['email'];
        $plainPassword = $credentials['password'];

        $this->authenticator->authenticate(Credentials::create($email, $plainPassword));

        return $userProvider->loadUserByUsername($email);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // TODO: Implement checkCredentials() method.
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        $this->router->generate(self::SUCCESS_REDIRECT);
    }

    protected function getLoginUrl()
    {
        return $this->router->generate(self::LOGIN_ROUTE);
    }
}