<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\EventSubscriber;


use Doctrine\ORM\EntityManagerInterface;
use Nurschool\Event\UserProfileEvent;
use Nurschool\Model\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserProfileSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var UserPasswordEncoderInterface */
    protected $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            UserProfileEvent::UPDATE_USER_PROFILE => 'updateUserProfile',
            UserProfileEvent::CHANGE_USER_PASSWORD => 'changeUserPassword'
        ];
    }

    public function changeUserPassword(UserProfileEvent $event)
    {
        $request = $event->getRequest();
        $form = $event->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UserInterface $user */
            $user = $form->getData();
            // encode the plain password
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $this->saveUser($user);

            $request->getSession()->getFlashBag()->add('success', 'Has modificado tu contraseñas correctamente.');

            $event->setResponse(new RedirectResponse($request->getRequestUri()));
        }

    }

    public function updateUserProfile(UserProfileEvent $event)
    {
        $request = $event->getRequest();
        $form = $event->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $this->saveUser($user);

            $request->getSession()->getFlashBag()->add('success', 'Has modificado tus datos correctamente.');

            $event->setResponse(new RedirectResponse($request->getRequestUri()));
        }
    }

    private function saveUser($user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}