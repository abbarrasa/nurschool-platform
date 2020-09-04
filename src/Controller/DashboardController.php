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

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Nurschool\Entity\Enquiry;
use Nurschool\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(): Response
    {
//        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_NURSE')) {
//            $editProfileResponse = $this->forward('fos_user.profile.controller:editAction');
//            return $this->render('@EasyAdmin/welcome.html.twig', [
//                'edit_profile' => $editProfileResponse->getContent()
//            ]);
//        }

//
//        return $this->render('asdfdsfsdfsddsf');

        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Nurschool');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Blog');
        yield MenuItem::linkToCrud('User', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('The Label', 'fa fa-home', Enquiry::class);
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        // Usually it's better to call the parent method because that gives you a
        // user menu with some menu items already created ("sign out", "exit impersonation", etc.)
        // if you prefer to create the user menu from scratch, use: return UserMenu::new()->...
        return parent::configureUserMenu($user)
            // you can use any type of menu item, except submenus
            ->addMenuItems([
                MenuItem::linkToRoute('My Profile', 'fa fa-id-card', 'fos_user_profile_edit'),
            ]);
    }
}
