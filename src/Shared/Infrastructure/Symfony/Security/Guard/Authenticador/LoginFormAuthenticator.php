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

use Nurschool\User\Application\Command\Auth\AuthUserCommand;
use Nurschool\Shared\Application\Command\CommandBus;
use Nurschool\Shared\Application\Query\QueryBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

final class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    private const LOGIN_ROUTE = 'login';

    private const SUCCESS_REDIRECT = 'profile';

    /** @var UrlGeneratorInterface */
    private $router;

    private $commandBus;

    private $queryBus;

    public function __construct(
        UrlGeneratorInterface $router,
        CommandBus $commandBus,
        QueryBus $queryBus
    ) {
        $this->router = $router;
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
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

            $this->commandBus->dispatch(AuthUserCommand::create($email, $plainPassword));

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