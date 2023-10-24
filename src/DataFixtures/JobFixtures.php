<?php

namespace App\DataFixtures;

use App\Entity\UserJob;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class JobFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $userJobs = [
            [
                "label" => "Directeur"
            ],

            [
                "label" => "Supports"
            ],

            [
                "label" => "Salarié"
            ]
        ];

        foreach ($userJobs as $job){
            $entity = new UserJob();
            $entity->setLabel($job['label']);
            $entity->setCreatedAt(new \DateTime('now'));
            $manager->persist($entity);
        }

        $manager->flush();
    }
}
