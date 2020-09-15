<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Util;


use Doctrine\ORM\EntityManagerInterface;
use Nurschool\Entity\School;
use Nurschool\Form\WelcomeConfigAdminFormType;
use Nurschool\Form\WelcomeConfigNurseFormType;
use Nurschool\Form\WelcomeUserProfileFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class WelcomeProcessor
{
    /** @var Security */
    protected $security;

    /** @var FormFactoryInterface */
    protected $formFactory;

    /** @var EntityManagerInterface */
    protected $entityManager;

    public function __construct(Security $security, FormFactoryInterface $formFactory, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
    }

    public function processRequest(Request $request): Response
    {
        if (null !== ($form = $this->createResquestedForm($request))) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                return $this->processSubmitedForm($request, $form);
            }
        }

        return $this->render('@EasyAdmin/welcome.html.twig');
    }


    protected function createResquestedForm(Request $request): FormInterface
    {
        $form = $this->formFactory->createNamed('UserProfileForm', WelcomeUserProfileFormType::class, $this->security->getUser());
        $form = $this->formFactory->createNamed('AdminConfigForm', WelcomeConfigAdminFormType::class, new School());
        $form = $this->formFactory->create('NurseConfigForm', WelcomeConfigNurseFormType::class, $this->security->getUser());

        return $form;
    }

    protected function processSubmitedForm(FormInterface $form): Response
    {
        if ('UserProfileForm' === $form->getName()) {
            $user = $form->getData();
            $this->performanceUserUpdate($user);
            if ($user->hasRole('ROLE_ADMIN')) {
                return $this->redirectToRoute('we');
            }

            return $this->redirectToRoute('welcome_config_nurse');

        } elseif ('AdminConfigForm' === $form->getName()) {

        } elseif ('NurseConfigForm' === $form->getName()) {

        }

        return $this->render('@EasyAdmin/welcome.html.twig');
    }

    protected function performanceUserUpdate(UserInterface $user): void
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }
}