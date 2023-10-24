<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class ProfileFixtures.
 */
class ProfileFixtures extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        $profileRoles = [

            [
                'name' => 'La Direction générale',
                'acronyme' => 'DG',
                'roles' => ['ROLE_PROJECT_READ', 'ROLE_PROJECT_WRITE'],
            ],
            [
                'name' => 'Les fonctions supports',
                'acronyme' => 'FS',
                'roles' => ['ROLE_PROJECT_READ', 'ROLE_PROJECT_WRITE', 'ROLE_PROJECT_CLOSE_DEMAND', 'ROLE_PROJECT_READ_CLOSED'],
            ],
            [
                'name' => 'Les salariés',
                'acronyme' => 'SA',
                'roles' => ['ROLE_PROJECT_READ', 'ROLE_PROJECT_WRITE'],
            ],

        ];

        foreach ($profileRoles as $profileRole) {
            // Create entity
            $entity = new Profile();
            $entity->setName($profileRole['name']);
            $entity->setAcronyme($profileRole['acronyme']);
            $entity->setCreatedAt(new \DateTime('now'));

            $manager->persist($entity);
        }

        $manager->flush();
    }
}
