<?php

namespace Nurschool\Wizard\Stage;

use Nurschool\Wizard\Exception\AbortStageException;

interface StageInterface
{
    /**
     * The stage name
     */
    public function getName(): string;

    /**
     * The stage's full template name, e.g. 'AcmeDemoBundle:Stage:prep.html.twig'
     */
    public function getTemplateName(): string;

    /**
     * Logic to determine if the stage is required or can be skipped
     *
     * @throws AbortStageException
     */
    public function isNecessary(): bool;

    /**
     * An array of template parameters required in the stage template
     */
    public function getTemplateParams(): array;
}
