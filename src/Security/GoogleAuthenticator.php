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

use Doctrine\ORM\EntityManagerInterface;
use Nurschool\Entity\User;
use Nurschool\Event\Oauth2UserRegisteredEvent;
use Nurschool\Event\RegisteredUserEvent;
use Nurschool\Model\UserManagerInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\GoogleUser;
use Nurschool\Security\Util\AuthenticationSuccessTrait;
use Nurschool\Manager\AvatarManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class GoogleAuthenticator extends SocialAuthenticator
{
    use TargetPathTrait;
    use AuthenticationSuccessTrait;

    /** @var ClientRegistry */
    private $clientRegistry;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var AvatarManager */
    private $avatarManager;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /**
     * GoogleAuthenticator constructor.
     * @param ClientRegistry $clientRegistry
     * @param EntityManagerInterface $entityManager
     * @param AvatarManager $avatarManager
     * @param UrlGeneratorInterface $urlGenerator
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        ClientRegistry $clientRegistry,
        EntityManagerInterface $entityManager,
        AvatarManager $avatarManager,
        UrlGeneratorInterface $urlGenerator,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->avatarManager = $avatarManager;
        $this->urlGenerator = $urlGenerator;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request)
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'connect_google_check';
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
        $repository = $this->entityManager->getRepository(User::class);
        $user = $repository->findOneBy(['googleUid' => $googleUser->getId()]);
        if (!$user) {
            // 2) do we have  a matching user by email?
            $email = $googleUser->getEmail();
            $user = $repository->findOneBy(['email' => $email]);
            // 3) Maybe you just want to "register" them by creating
            // a User object
            if (!$user) {
                $user = new User();
                $user
                    ->setEmail($email)
                    ->setGoogleUid($googleUser->getId())
                    ->setFirstname($googleUser->getFirstName())
                    ->setLastname($googleUser->getLastName())
                ;

                if (null !== ($uri = $googleUser->getAvatar())) {
                    $this->avatarManager->setAvatarFromUri($uri, $user);
                }

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                // Launch an event when user account is created
                $this->eventDispatcher->dispatch(new RegisteredUserEvent($user), RegisteredUserEvent::NAME);
            } else {
                $user->setGoogleUid($googleUser->getId());

                $this->entityManager->persist($user);
                $this->entityManager->flush();
            }
        }

        return $userProvider->loadUserByUsername($user->getEmail());
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
        $session = $request->getSession();
        return $this->getAuthenticatedResponse(
            $session,
            $token->getUser(),
            $this->getTargetPath($session, $providerKey)
        );
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
        return new RedirectResponse($this->urlGenerator->generate('login'));
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
            $this->urlGenerator->generate('login'), // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }

    /**
     * @return GoogleClient
     */
    private function getClient()
    {
        return $this->clientRegistry->getClient('google');
    }
}