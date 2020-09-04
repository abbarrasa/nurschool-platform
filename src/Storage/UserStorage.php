<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Storage;


use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManager;
use FOS\UserBundle\Util\CanonicalFieldsUpdater;
use FOS\UserBundle\Util\PasswordUpdaterInterface;
use Nurschool\Model\UserManagerInterface;

class UserStorage extends UserManager implements UserManagerInterface
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var string */
    private $class;

    /**
     * AbstractStorage constructor.
     * @param PasswordUpdaterInterface $passwordUpdater
     * @param CanonicalFieldsUpdater $canonicalFieldsUpdater
     * @param EntityManagerInterface $em
     * @param string $class
     */
    public function __construct(
        PasswordUpdaterInterface $passwordUpdater,
        CanonicalFieldsUpdater $canonicalFieldsUpdater,
        EntityManagerInterface $em,
        string $class
    ) {
        parent::__construct($passwordUpdater, $canonicalFieldsUpdater);
        $this->entityManager = $em;
        $this->class = $class;
    }

    public function getClass()
    {
        if (false !== strpos($this->class, ':')) {
            $metadata = $this->entityManager->getClassMetadata($this->class);
            $this->class = $metadata->getName();
        }

        return $this->class;
    }

    public function findUserBy(array $criteria)
    {
        return $this->entityManager->getRepository($this->getClass())->findOneBy($criteria);
    }

    public function findUsers()
    {
        return $this->entityManager->getRepository($this->getClass())->findAll();
    }

//    public function findUserByPhoneOrEmail($phoneOrEmail)
//    {
//        // TODO: Implement findUserByPhoneOrEmail() method.
//    }

    public function findUserByPhone($phone)
    {
        // TODO: Implement findUserByPhone() method.
    }

    public function reloadUser(UserInterface $user)
    {
        $this->entityManager->refresh($user);
    }

    public function updateUser(UserInterface $user, bool $andFlush = true)
    {
        $this->updateCanonicalFields($user);
        $this->updatePassword($user);

        $this->entityManager->persist($user);
        if ($andFlush) {
            $this->entityManager->flush();
        }
    }

    public function deleteUser(UserInterface $user)
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}