<?php

namespace Nurschool\Stage;


class WelcomePrepStage implements StageInterface
{
    public function getName(): string
    {
        return 'prep';
    }

    public function getTemplateName(): string
    {
        return '@EasyAdmin/welcome.html.twig';
    }

    public function isNecessary(): bool
    {
        return true;
    }

    public function getTemplateParams(): array
    {
        return [];
    }
}