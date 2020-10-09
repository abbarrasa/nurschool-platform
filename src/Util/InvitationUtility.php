<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Util;


use Nurschool\Generator\InvitationCodeGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvitationUtility
{
    protected $codeGenerator;

    protected $session;

    public function __construct(InvitationCodeGeneratorInterface $codeGenerator, Session $session)
    {
        $this->codeGenerator = $codeGenerator;
        $this->session = $session;
    }

}