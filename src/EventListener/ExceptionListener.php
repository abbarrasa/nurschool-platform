<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\EventListener;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class ExceptionListener
{
    /** @var Security */
    protected $security;

    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    public function __construct(Security $security, UrlGeneratorInterface $urlGenerator)
    {
        $this->security = $security;
        $this->urlGenerator = $urlGenerator;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();
        if ($exception instanceof AccessDeniedHttpException) {
            $user = $this->security->getUser();
            if (!$user->isVerified() || !$user->hasAnyRole())  {
                $event->setResponse(new RedirectResponse($this->urlGenerator->generate('welcome')));
            }
        }
    }
}