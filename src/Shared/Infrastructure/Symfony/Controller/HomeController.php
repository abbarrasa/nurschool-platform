<?php


namespace Nurschool\Shared\Infrastructure\Symfony\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function home(): Response
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/dashboard", name="dashboard")
     * @return Response
     */
    public function dashboard(): Response
    {
        return $this->render('home/dashboard.html.twig');
    }
}