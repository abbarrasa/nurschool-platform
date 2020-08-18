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


//use App\Events;
//use App\Media\AvatarManager;
use Nurschool\Model\UserInterface;
use Nurschool\Model\UserManagerInterface;
use FOS\UserBundle\Event\UserEvent;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\FacebookUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class FacebookAuthenticator extends SocialAuthenticator
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
     * FacebookAuthenticator constructor.
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

    public function supports(Request $request)
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'connect_check';
    }

    public function getCredentials(Request $request)
    {
        // this method is only called if supports() returns true

        return $this->fetchAccessToken($this->getClient());
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var FacebookUser $facebookUser */
        $facebookUser = $this->getClient()->fetchUserFromToken($credentials);
        $user = $this->userManager->findUserByFacebookUid($facebookUser->getId());

        if (!$user) {
            $email = $facebookUser->getEmail();
            $user  = $this->userManager->findUserByEmail($email);

            if (!$user) {
                /** @var UserInterface $user */
                $user = $this->userManager->createUser();
                $user->setEnabled(true);
                $user->addRole('ROLE_ADMIN');
                $user->setEmail($email);
                $user->setFirstname($facebookUser->getFirstName());
                $user->setLastname($facebookUser->getLastName());

                $this->eventDispatcher->dispatch(new UserEvent($user), Events::OAUTH2_REGISTRATION_SUCCESS);
            }

            $user->setFacebookUid($facebookUser->getId());
        }
         
//        $this->avatarGenerator->updateAvatarFromUrl($user, $facebookUser->getPictureUrl());

        $this->userManager->save($user);

        return $userProvider->loadUserByUsername($user->getUsername());
    }
    
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->router->generate('home'));

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
            $this->router->generate('fos_user_security_login'), // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

    /**
     * @return FacebookClient
     */
    private function getClient()
    {
        // "facebook" is the key used in config/packages/knpu_oauth2_client.yaml
        return $this->clientRegistry->getClient('facebook');
    }
}