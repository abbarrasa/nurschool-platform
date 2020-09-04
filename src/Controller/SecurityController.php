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


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\UserBundle\Controller\SecurityController as FOSUserSecurityController;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends AbstractController
{
    private $securityController;

    public function __construct(FOSUserSecurityController $securityController)
    {
        $this->securityController = $securityController;
    }

    public function loginAction(Request $request)
    {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('dashboard');
        }

        return $this->securityController->loginAction($request);
    }

    public function checkAction()
    {
        return $this->securityController->checkAction();
    }

    public function logoutAction()
    {
        return $this->securityController->logoutAction();
    }

}