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


use Doctrine\ORM\EntityManagerInterface;
use Nurschool\Event\ChangedRolesEvent;
use Nurschool\Form\WelcomeUserProfileFormType;
use Nurschool\Model\UserInterface;
use Nurschool\Util\AvatarGenerator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Security;

class WelcomeProfileStage implements StageInterface, FormHandlerInterface
{
    private $security;
    private $formFactory;
    private $entityManager;
    private $avatarGenerator;
    private $eventDispatcher;

    /**
     * WelcomeProfileStage constructor.
     * @param Security $security
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $entityManager
     * @param AvatarGenerator $avatarGenerator
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        Security $security,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        AvatarGenerator $avatarGenerator,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->security = $security;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->avatarGenerator = $avatarGenerator;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getName(): string
    {
        return 'profile';
    }

    public function getTemplateName(): string
    {
        return '@EasyAdmin/stages/welcome_profile.html.twig';
    }

    public function isNecessary(): bool
    {
        return !$this->security->getUser()->hasAnyRole();
    }

    public function getTemplateParams(): array
    {
        return [];
    }

    public function getFormType()
    {
        return $this->formFactory->create(WelcomeUserProfileFormType::class, $this->security->getUser(), $this->getFormOptions());
    }

    public function handleFormResult(FormInterface $form): bool
    {
        /** @var UserInterface $user */
        $user = $form->getData();
        if (null === $user->getAvatar()) {
            $this->avatarGenerator->setInitialAvatar($user);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new ChangedRolesEvent($user), ChangedRolesEvent::NAME);

        return true;
    }

    public function getFormOptions(): array
    {
        return [];
    }
}