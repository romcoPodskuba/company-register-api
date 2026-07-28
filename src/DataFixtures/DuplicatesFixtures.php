<?php

namespace App\DataFixtures;

use App\Entity\Duplicates;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DuplicatesFixtures extends Fixture
{
    private const COUNT = 100;
    private const MIN_VALUE = 1;
    private const MAX_VALUE = 50;

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::COUNT; $i++) {
            $duplicate = new Duplicates();
            $duplicate->setValue(rand(self::MIN_VALUE, self::MAX_VALUE));
            $manager->persist($duplicate);
        }

        $manager->flush();
    }
}
