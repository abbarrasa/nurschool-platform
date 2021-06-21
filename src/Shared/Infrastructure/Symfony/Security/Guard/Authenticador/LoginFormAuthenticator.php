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

use Nurschool\Shared\Application\Command\CommandBus;
use Nurschool\User\Application\Command\Auth\AuthUserCommand;
use Nurschool\User\Application\Command\Auth\BadCredentials;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
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

    /** @var CommandBus */
    private $commandBus;

    public function __construct(
        UrlGeneratorInterface $router,
        CsrfTokenManagerInterface $tokenManager,
        CommandBus $commandBus
    ) {
        $this->router = $router;
        $this->tokenManager = $tokenManager;
        $this->commandBus = $commandBus;
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
            'csrf_token' => $request->request->get('csrf_token'),
        ];

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $email = $credentials['email'];
        $plainPassword = $credentials['password'];
        $csrfToken = $credentials['csrf_token'];

        $token = new CsrfToken('authenticate', $csrfToken);
        if (!$this->tokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        try {
            $this->commandBus->dispatch(AuthUserCommand::create($email, $plainPassword));

            return $userProvider->loadUserByUsername($email);
        } catch(BadCredentials $exception) {
            throw new BadCredentialsException();
        }
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
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