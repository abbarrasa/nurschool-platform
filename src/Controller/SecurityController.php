<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Controller;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /**
     * GoogleController constructor.
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/connect/google", name="connect_google_start")
     *
     * Link to this controller to start the "connect" process to Google
     * 
     * @param ClientRegistry $clientRegistry
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function googleConnect(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('google')
            ->redirect([
                'profile', 'email' // the scopes you want to access
            ])
        ;
    }

    /**
     * @Route("/connect/facebook", name="connect_facebook_start")
     *
     * Link to this controller to start the "connect" process to Facebook
     *
     * @param ClientRegistry $clientRegistry
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function facebookConnect(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('facebook')
            ->redirect([
                'public_profile', 'email' // the scopes you want to access
            ])
        ;
    }

    /**
     * @Route("/connect/google/check", name="connect_google_check")
     *
     * After going to Google, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function connectGoogleCheck(Request $request/*, ClientRegistry $clientRegistry*/)
    {
//        die(sprintf("Entra en %s", __FUNCTION__));
        $email = $request->getSession()->get('fos_user_send_confirmation_email/email');
        if (empty($email)) {
            return $this->redirectToRoute('dashboard');
        }

        return $this->redirectToRoute('fos_user_registration_check_email');
    }
}