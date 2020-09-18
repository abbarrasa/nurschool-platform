<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Based in parts of the Zikula package <https://ziku.la/>
 */

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
