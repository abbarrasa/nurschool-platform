<?php

namespace Nurschool\Wizard\Stage;


use Doctrine\ORM\EntityManagerInterface;
use Nurschool\Form\WelcomeUserProfileFormType;
use Nurschool\Wizard\Exception\AbortStageException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Security;

class WelcomeProfileStage implements StageInterface, FormHandlerInterface
{
    private $security;
    private $formFactory;
    private $entityManager;

    /**
     * WelcomeGetInfoStage constructor.
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
        return 'profile';
    }

    public function getTemplateName(): string
    {
        return '@EasyAdmin/stages/welcome_profile.html.twig';
    }

    public function isNecessary(): bool
    {
        return !$this->security->getUser()->hasAnyRole();
    }

    public function getTemplateParams(): array
    {
        return [];
    }

    public function getFormType()
    {
        return $this->formFactory->create(WelcomeUserProfileFormType::class, $this->security->getUser(), $this->getFormOptions());
    }

    public function handleFormResult(FormInterface $form): bool
    {
        $user = $form->getData();
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return true;
    }

    public function getFormOptions(): array
    {
        return [];
    }

}