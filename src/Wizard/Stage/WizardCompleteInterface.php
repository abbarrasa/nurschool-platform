<?php

namespace Nurschool\Wizard\Stage;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface WizardCompleteInterface
{
    /**
     * Get the Response (probably RedirectResponse) for this completed Wizard.
     *
     * @param Request $request
     * @return Response
     */
    public function getResponse(Request $request): Response;
}
