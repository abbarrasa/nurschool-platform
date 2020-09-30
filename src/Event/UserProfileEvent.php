<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Event;


use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class UserProfileEvent extends Event
{
    public const CHANGE_USER_PASSWORD = 'nurschool.user.change_password';
    public const UPDATE_USER_PROFILE = 'nurschool.user.update_profile';

    /** @var Request */
    private $request;

    /** @var FormInterface */
    private $form;

    /** @var Response */
    private $response;

    public function __construct(Request $request, FormInterface $form)
    {
        $this->request = $request;
        $this->form = $form;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return FormInterface
     */
    public function getForm(): FormInterface
    {
        return $this->form;
    }

    /**
     * @return Response
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }
}