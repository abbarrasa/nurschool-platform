<?php

namespace Nurschool\Stage;


use Nurschool\Entity\School;
use Nurschool\Form\WelcomeConfigAdminFormType;

class WelcomeConfigAdminStage /*implements StageInterface, FormHandlerInterface*/
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
        return 'configadmin';
    }

    public function getTemplateName(): string
    {
        return '@EasyAdmin/stages/welcome_config_admin.html.twig';
    }

    public function isNecessary(): bool
    {
        return $this->security->isGranted('ROLE_ADMIN');
    }

    public function getTemplateParams(): array
    {
        return [];
    }

    public function getFormType(): string
    {
        return $this->formFactory->create(WelcomeConfigAdminFormType::class, new School(), $this->getFormOptions());
    }

    /**
     * Handle results of previously validated form
     */
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

    /**
     * Returns an array of options applied to the Form.
     */
    public function getFormOptions(): array
    {
        return [];
    }

}