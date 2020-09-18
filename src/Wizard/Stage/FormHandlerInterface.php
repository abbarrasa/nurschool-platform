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

use Symfony\Component\Form\FormInterface;

interface FormHandlerInterface
{
    /**
     * Returns the FQCN of a Symfony Form Type
     *
     * @return string|FormInterface
     */
    public function getFormType();

    /**
     * Handle results of previously validated form
     *
     * @param FormInterface $form
     * @return bool
     */
    public function handleFormResult(FormInterface $form): bool;

    /**
     * Returns an array of options applied to the Form.
     *
     * @return array
     */
    public function getFormOptions(): array;
}
