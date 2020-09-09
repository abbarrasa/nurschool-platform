<?php

namespace Nurschool\Controller;

use Nurschool\Entity\Enquiry;
use Nurschool\Form\EnquiryFormType;
use Nurschool\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private const STATUS_FORM_SUCCESS = 'Success';
    private const STATUS_FORM_FAILED = 'Failed';
    private const STATUS_FORM_NOT_SUBMITTED = 'Not submitted';

    /**
     * @Route("/", name="home")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $form =$this->createForm(EnquiryFormType::class, new Enquiry());
        $result = $this->processEnquiryForm($request, $form);
        if (self::STATUS_FORM_SUCCESS == $result) {
            return $this->redirectToRoute('home');
        } elseif (self::STATUS_FORM_FAILED == $result) {
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'enquiryForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/contact-us", name="contact_us")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contactUs(Request $request)
    {
        $form =$this->createForm(EnquiryFormType::class, new Enquiry());
        if (self::STATUS_FORM_SUCCESS == $this->processEnquiryForm($request, $form)) {
            return $this->redirectToRoute('contact');
        }

        return $this->render('home/contact.html.twig', [
            'controller_name' => 'HomeController',
            'enquiryForm' => $form->createView()
        ]);
    }

    protected function processEnquiryForm(Request $request, FormInterface $form)
    {
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                return self::STATUS_FORM_FAILED;
            }

            $entityManager = $this->getDoctrine()->getManager();
            $enquiry = $form->getData();
            $entityManager->persist($enquiry);
            $entityManager->flush();

            return self::STATUS_FORM_SUCCESS;
        }

        return self::STATUS_FORM_NOT_SUBMITTED;
    }
}
