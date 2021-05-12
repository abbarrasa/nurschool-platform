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


use Assert\AssertionFailedException;
use InvalidArgumentException;
use Nurschool\User\Application\Command\Auth\AuthUserCommand;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Throwable;
use UnexpectedValueException;

final class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    private const LOGIN_ROUTE = 'login';

    private const SUCCESS_REDIRECT = 'profile';

    /** @var UrlGeneratorInterface */
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function supports(Request $request): bool
    {
        return $request->getPathInfo() === $this->router->generate(self::LOGIN_ROUTE) &&
            $request->isMethod('POST')
        ;
    }

    public function getCredentials(Request $request): array
    {
        return [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            $email = $credentials['email'];
            $plainPassword = $credentials['password'];

            $signInCommand = new AuthUserCommand($email, $plainPassword);

        } catch(\Exception $exception) {

        }
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // TODO: Implement checkCredentials() method.
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        // TODO: Implement onAuthenticationSuccess() method.
    }

    protected function getLoginUrl()
    {
        return $this->router->generate(self::LOGIN_ROUTE);
    }
}