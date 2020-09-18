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
