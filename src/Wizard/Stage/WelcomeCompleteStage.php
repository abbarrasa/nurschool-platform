<?php


namespace Nurschool\Stage;


class WelcomeCompleteStage implements StageInterface, WizardCompleteInterface
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function getName(): string
    {
        return 'complete';
    }

    public function getTemplateName(): string
    {
        return '@EasyAdmin/stage/welcome_complete.html.twig';
    }

    public function isNecessary(): bool
    {
        return true;
    }

    public function getTemplateParams(): array
    {
        return [];
    }    
    
    public function getResponse(Request $request): Response
    {
        return new RedirectResponse($this->urlGenerator->generate('dashboard'));
    }
}