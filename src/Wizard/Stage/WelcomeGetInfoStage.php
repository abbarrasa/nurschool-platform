<?php

namespace Nurschool\Stage;


use Nurschool\Form\WelcomeUserProfileFormType;

class WelcomeGetInfoStage implements StageInterface, FormHandlerInterface
{
    private $security;
    private $formFactory;
    private $entityManager;

    public function __construct(Security $security, FormFactoryInterface $formFactory, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
    }

    public function getName(): string
    {
        return 'getinfo';
    }

    public function getTemplateName(): string
    {
        return '@EasyAdmin/stages/welcome_get_info.html.twig';
    }

    public function isNecessary(): bool
    {
        return !$this->security->getUser()->hasAnyRole();
    }

    public function getTemplateParams(): array
    {
        return [];
    }

    public function getFormType(): string
    {
        return $this->formFactory->create(WelcomeUserProfileFormType::class, $this->security->getUser(), $this->getFormOptions());
    }

    /**
     * Handle results of previously validated form
     */
    public function handleFormResult(FormInterface $form): bool
    {
        $user = $form->getData();
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return true;
    }

    /**
     * Returns an array of options applied to the Form.
     */
    public function getFormOptions(): array
    {
        return [];
    }

}