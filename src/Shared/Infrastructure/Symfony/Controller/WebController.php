<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Shared\Infrastructure\Symfony\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class WebController extends AbstractController
{
    protected function redirectWithMessage(
        string $message,
        string $routeName,
        array $parameters = []
    ): RedirectResponse {
        $this->addFlashFor('message', [$message]);

        return $this->redirectToRoute($routeName, $parameters);
    }

    protected function redirectWithErrors(
        Request $request,
        ConstraintViolationListInterface $violations,
        string $routeName,
        array $parameters = []
    ): RedirectResponse {
        $this->addFlashFor('errors', $this->formatFlashErrors($violations));
        $this->addFlashFor('inputs', $request->request->all());

//        var_dump($this->get('session')->getFlashBag());
//        die;

        return $this->redirectToRoute($routeName, $parameters);
    }

    private function formatFlashErrors(ConstraintViolationListInterface $violations): array
    {
        $errors = [];
        foreach ($violations as $violation) {
            $errors[str_replace(['[', ']'], ['', ''], $violation->getPropertyPath())] = $violation->getMessage();
        }

        return $errors;
    }

    private function addFlashFor(string $prefix, array $messages): void
    {
        foreach ($messages as $key => $message) {
            $this->addFlash($prefix . '.' . $key, $message);
        }
    }
}