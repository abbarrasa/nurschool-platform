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
use Nurschool\Entity\School;
use Nurschool\Form\WelcomeConfigAdminFormType;
use Nurschool\Wizard\Exception\AbortStageException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Security;

class WelcomeConfigAdminStage implements StageInterface, FormHandlerInterface
{
    private $security;
    private $formFactory;
    private $entityManager;

    /**
     * WelcomeConfigAdminStage constructor.
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
        return 'configAdmin';
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

        return $this->security->isGranted('ROLE_ADMIN');
    }

    public function getTemplateParams(): array
    {
        return [];
    }

    public function getFormType()
    {
        return $this->formFactory->create(WelcomeConfigAdminFormType::class, new School(), $this->getFormOptions());
    }

    public function handleFormResult(FormInterface $form): bool
    {
        /** @var School $school */
        $school = $form->getData();
        $nurses = $form->get('nurses')->getData();

        foreach($nurses as $nurse) {
            $school->addUser($nurse);
        }

        $this->entityManager->persist($school);
        $this->entityManager->flush();

        return true;
    }

    public function getFormOptions(): array
    {
        return [];
    }
}