<?php

namespace Nurschool\Security;

use Nurschool\Shared\Application\Command\CommandBus;
use Nurschool\User\Application\Command\Auth\AuthUserCommand;
use Nurschool\User\Application\Command\Auth\BadCredentials;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var UserProviderInterface */
    private $userProvider;

    /** @var CommandBus */
    private $commandBus;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        UserProviderInterface $userProvider,
        CommandBus $commandBus
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->userProvider = $userProvider;
        $this->commandBus = $commandBus;
    }

    public function authenticate(Request $request): PassportInterface
    {
        $email = $request->request->get('email_value', '');
        $password = $request->request->get('password', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

//        return new Passport(
//            new UserBadge($emailValue),
//            new PasswordCredentials($request->request->get('password', '')),
//            [
//                new CsrfTokenBadge('authenticate', $request->get('_csrf_token')),
//            ]
//        );

        return new SelfValidatingPassport(
            new UserBadge($email, function() use ($email, $password) {
                try {
                    $this->commandBus->dispatch(AuthUserCommand::create($email, $password));

                    return $this->userProvider->loadUserByUsername($email);
                } catch(BadCredentials $exception) {
                    throw new BadCredentialsException();
                }
            })
        );

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('dashboard'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('login');
    }
}
