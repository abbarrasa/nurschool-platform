<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Wizard\Stage;


use Nurschool\Wizard\Exception\AbortStageException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class WelcomeCompleteStage implements StageInterface, WizardCompleteInterface
{
    private $security;
    private $urlGenerator;

    /**
     * WelcomeCompleteStage constructor.
     * @param Security $security
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(Security $security, UrlGeneratorInterface $urlGenerator)
    {
        $this->security = $security;
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
        if (!$this->security->getUser()->hasAnyRole()) {
            throw new AbortStageException('User has not any role.');
        }

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