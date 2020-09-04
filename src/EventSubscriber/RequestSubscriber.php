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


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Util\TargetPathTrait;


class RequestSubscriber implements EventSubscriberInterface
{
    use TargetPathTrait;

    private $security;
    private $session;

    public function __construct(Security $security, SessionInterface $session)
    {
        $this->security = $security;
        $this->session = $session;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        
        if (
            !$event->isMasterRequest()
            || $request->isXmlHttpRequest()
            || 'fos_user_security_login' === $request->attributes->get('_route')
        ) {
            return;
        }

        $this->saveTargetPath($this->session, 'main', $request->getUri());
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest']
        ];
    }
}