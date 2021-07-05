<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Shared\Infrastructure\Symfony\Security\Guard\Authenticador;


use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use League\OAuth2\Client\Provider\GoogleUser;
use Nurschool\Shared\Application\Command\CommandBus;
use Nurschool\User\Application\Command\Create\CreateGoogleUserCommand;
use Nurschool\User\Application\Command\Create\CreateUserCommand;
use Nurschool\User\Application\Command\Update\SetGoogleIdCommand;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class GoogleAuthenticator extends SocialAuthenticator
{
    private const CHECK_ROUTE = 'connect_check';

    private const SUCCESS_REDIRECT = 'dashboard';


    private $clientRegistry;
    private $commandBus;
    private $router;

    public function __construct(
        ClientRegistry $clientRegistry,
        CommandBus $commandBus,
        RouterInterface $router
    ) {
        $this->clientRegistry = $clientRegistry;
        $this->commandBus = $commandBus;
        $this->router = $router;
    }

    public function supports(Request $request)
    {
        // continue ONLY if the current ROUTE matches the check ROUTE and client is google
        return
            $request->attributes->get('_route') === self::CHECK_ROUTE &&
            $request->attributes->get('client') === 'google'
        ;
    }

    public function getCredentials(Request $request)
    {
        // this method is only called if supports() returns true

        // For Symfony lower than 3.4 the supports method need to be called manually here:
        // if (!$this->supports($request)) {
        //     return null;
        // }

        return $this->fetchAccessToken($this->getGoogleClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var GoogleUser $googleUser */
        $googleUser = $this->getGoogleClient()->fetchUserFromToken($credentials);
        $googleId = $googleUser->getId();
        $user = $userProvider->loadUserByUsername($googleId);

        if (null === $user) {
            $email = $googleUser->getUser();
            $firstname = $googleUser->getFirstName();
            $lastname = $googleUser->getLastName();
            $user = $userProvider->loadUserByUsername($email);
            if (null === $user) {
                $command = new CreateUserCommand($email);
                $command->googleId = $googleId;
            } else {
                $command = new SetGoogleIdCommand($user, $googleId);
            }

            $command->firstname = $firstname;
            $command->lastname = $lastname;
            $this->commandBus->dispatch($command);
        }

        return $user;
    }

    /**
     * @return GoogleClient
     */
    private function getGoogleClient(): GoogleClient
    {
        return $this
            ->clientRegistry
            ->getClient('google')
        ;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $targetUrl = $this->router->generate(self::SUCCESS_REDIRECT);

        return new RedirectResponse($targetUrl);

        // or, on success, let the request continue to be handled by the controller
        //return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse(
            '/connect/', // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

    // ...

}