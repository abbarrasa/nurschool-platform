<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Wizard\Stage;


use Doctrine\ORM\EntityManagerInterface;
use Nurschool\Entity\JoinSchoolRequest;
use Nurschool\Form\WelcomeConfigAdminFormType;
use Nurschool\Wizard\Exception\AbortStageException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Security;

class WelcomeConfigNurseStage implements StageInterface, FormHandlerInterface
{
    private $security;
    private $formFactory;
    private $entityManager;

    /**
     * WelcomeConfigNurseStage constructor.
     * @param Security $security
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(Security $security, FormFactoryInterface $formFactory, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
    }

    public function getName(): string
    {
        return 'configNurse';
    }

    public function getTemplateName(): string
    {
        return '@EasyAdmin/stages/welcome_config_admin.html.twig';
    }

    public function isNecessary(): bool
    {
        if (!$this->security->getUser()->hasAnyRole()) {
            throw new AbortStageException('User has not any role.');
        }

        return !$this->security->isGranted('ROLE_ADMIN');
    }

    public function getTemplateParams(): array
    {
        return [];
    }

    public function getFormType()
    {
        $joinSchoolRequest = new JoinSchoolRequest();
        $joinSchoolRequest->setApplicant($this->security->getUser());
        $joinSchoolRequest->setRole('ROLE_NURSE');

        return $this->formFactory->create(WelcomeConfigAdminFormType::class, $joinSchoolRequest, $this->getFormOptions());
    }

    public function handleFormResult(FormInterface $form): bool
    {
        $this->entityManager->persist($form->getData());
        $this->entityManager->flush();

        return true;
    }

    public function getFormOptions(): array
    {
        return [];
    }
}