<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Goal;
use AppBundle\Entity\GoalNote;
use AppBundle\Entity\GoalType;
use AppBundle\Entity\TextType;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

class LoadFixtures implements FixtureInterface
{
    //TODO php bin/console doctrine:fixtures:load

    public function load(ObjectManager $manager)
    {
        $objects = Fixtures::load(
            __DIR__.'/fixtures.yml',
            $manager,
            [
                'providers' => [$this]
            ]
        );
    }

    public function habits_name()
    {
        $name = [
            'Цель 1',
            'Цель 2',
            'Цель 3',
            'Цель 4',
            'Цель 5',
            'Цель 6',
            'Цель 7',
            'Цель 8',
            'Цель 9',
            'Цель 10',
        ];

        $key = array_rand($name);

        return $name[$key];
    }
}