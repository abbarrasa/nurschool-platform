<?php

namespace Nurschool\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Nurschool\Entity\Group;

class GroupFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $groupAdmins = new Group('Administrators', ['ROLE_ADMIN']);
        $groupNurses = new Group('Nurses', ['ROLE_NURSE']);

        $manager->persist($groupAdmins);
        $manager->persist($groupNurses);
        $manager->flush();
    }
}
