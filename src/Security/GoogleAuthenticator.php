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

use Nurschool\Model\UserInterface;
use Nurschool\Model\UserManagerInterface;
//use App\Entity\User;
//use App\Events;
//use App\Media\AvatarManager;
use FOS\UserBundle\Event\UserEvent;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class GoogleAuthenticator extends SocialAuthenticator
{
    /** @var ClientRegistry */
    private $clientRegistry;
    /** @var UserManagerInterface */
    private $userManager;
//    /** @var AvatarManager */
//    private $avatarGenerator;
    /** @var RouterInterface */
    private $router;
    /** @var EventDispatcherInterface  */
    private $eventDispatcher;

    /**
     * GoogleAuthenticator constructor.
     * @param ClientRegistry $clientRegistry
     * @param UserManagerInterface $userManager
//     * @param AvatarManager $avatarGenerator
     * @param RouterInterface $router
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        ClientRegistry $clientRegistry,
        UserManagerInterface $userManager,
//        AvatarManager $avatarGenerator,
        RouterInterface $router,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->clientRegistry = $clientRegistry;
        $this->userManager = $userManager;
//        $this->avatarGenerator = $avatarGenerator;
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request)
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'connect_check';
    }

    /**
     * @param Request $request
     * @return \League\OAuth2\Client\Token\AccessToken|mixed
     */
    public function getCredentials(Request $request)
    {
        // this method is only called if supports() returns true
        return $this->fetchAccessToken($this->getClient());
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return \Symfony\Component\Security\Core\User\UserInterface|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var GoogleUser $googleUser */
        $googleUser = $this->getClient()->fetchUserFromToken($credentials);
        // 1) have they logged in with Google before? Easy!
        $user = $this->userManager->findUserByGoogleUid($googleUser->getId());

        if (!$user) {
            // 2) do we have  a matching user by email?
            $email = $googleUser->getEmail();
            $user  = $this->userManager->findUserByEmail($email);
            // 3) Maybe you just want to "register" them by creating
            // a User object
            if (!$user) {
                /** @var UserInterface $user */
                $user = $this->userManager->createUser();
                $user->setEnabled(true);
                $user->addRole('ROLE_ADMIN');
                $user->setEmail($email);
                $user->setFirstname($googleUser->getFirstName());
                $user->setLastname($googleUser->getLastName());

                $this->eventDispatcher->dispatch(new UserEvent($user), Events::OAUTH2_REGISTRATION_SUCCESS);
            }

            $user->setGoogleUid($googleUser->getId());
        }

//        $this->avatarGenerator->updateAvatarFromUrl($user, $googleUser->getAvatar());

        $this->userManager->save($user);

        return $userProvider->loadUserByUsername($user->getUsername());
    }

    /**
     * Called when authentication executed and was successful!
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the last page they visited.
     *
     * If you return null, the current request will continue, and the user
     * will be authenticated. This makes sense, for example, with an API.
     *
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return null|Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue
        return new RedirectResponse($this->router->generate('home'));
//        return null;
    }

    /**
     * Called when authentication executed, but failed (e.g. wrong username password).
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the login page or a 403 response.
     *
     * If you return null, the request will continue, but the user will
     * not be authenticated. This is probably not what you want to do.
     *
     * @param Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $exception
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     *
     * @param Request $request
     * @param AuthenticationException|null $authException
     *
     * @return RedirectResponse
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse(
            $this->router->generate('fos_user_security_login'), // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

    /**
     * @return GoogleClient
     */
    private function getClient()
    {
        return $this->clientRegistry->getClient('google');
    }
}