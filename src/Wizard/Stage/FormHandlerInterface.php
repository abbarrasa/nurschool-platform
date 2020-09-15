<?php

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
