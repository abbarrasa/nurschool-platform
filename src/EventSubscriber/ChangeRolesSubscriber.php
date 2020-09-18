<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\EventSubscriber;


use Nurschool\Event\ChangedRolesEvent;
use Nurschool\Security\LoginFormAuthenticator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class ChangeRolesSubscriber  implements EventSubscriberInterface
{
    protected $guardHandler;
    protected $authenticator;
    protected $requestStack;

    public function __construct(RequestStack $requestStack, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator)
    {
        $this->guardHandler = $guardHandler;
        $this->authenticator = $authenticator;
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents()
    {
        return [
            ChangedRolesEvent::NAME => 'keepUserSession'
        ];
    }

    public function keepUserSession(ChangedRolesEvent $event)
    {
        $response = $this->guardHandler->authenticateUserAndHandleSuccess(
            $event->getUser(),
            $this->requestStack->getCurrentRequest(),
            $this->authenticator,
            'main'
        );

        $event->setResponse($response);
    }
}