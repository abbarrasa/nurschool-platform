<?php

namespace Nurschool\Controller;

use Nurschool\Entity\Enquiry;
use Nurschool\Form\EnquiryFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        return $this->buildAndProcessEnquiryForm($request);
    }

    /**
     * @Route("/contact-us", name="contact")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contact(Request $request)
    {
        return $this->buildAndProcessEnquiryForm($request);
    }

    protected function buildAndProcessEnquiryForm(Request $request)
    {
        $enquiry = new Enquiry();
        $form = $this->createForm(EnquiryFormType::class, $enquiry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($enquiry);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->renderPage($request, [
            'controller_name' => 'HomeController',
            'enquiryForm' => $form->createView()
        ]);
    }

    protected function renderPage(Request $request, array $parameters = [])
    {
        $route = $request->attributes->get('_route');
        if ($route == 'contact') {
            $view = 'home/contact.html.twig';
        } else {
            $view = 'home/index.html.twig';
        }

        return $this->render($view, $parameters);
    }
}
