<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        $rolesList = [
            'ROLE_PRESEDENTWORD_READ' => [
                'ROLE_PRESEDENTWORD_WRITE',
                'ROLE_PRESEDENTWORD_ARCHIVE',
            ],
            'ROLE_VIDEO_READ' => [
                'ROLE_VIDEO_WRITE',
                'ROLE_VIDEO_ARCHIVE',
            ],
        ];

        $i = 10;

        // Roles parents
        foreach ($rolesList as $code => $children_roles) {
            $role = new Role();
            $role->setCode($code);
            $role->setPosition($i);

            $i += 5;

            $manager->persist($role);

            // Roles Enfants
            if (is_array($children_roles)) {
                $this->createRecursiveRole($children_roles, $role, $manager, $i);
            }
        }
        $manager->flush();
    }

    /**
     * @param mixed $roles
     */
    private function createRecursiveRole($roles, Role $parent_role, ObjectManager $manager, int $position): void
    {
        // vide ou string
        if (empty($roles) || !is_array($roles)) {
            return;
        }

        // Roles parents
        foreach ($roles as $code => $children_roles) {
            $role = new Role();
            $role->setCode(is_array($children_roles) ? $code : $children_roles);
            $role->setPosition($position);

            $position += 5;

            // parent
            $role->setParent($parent_role);

            $manager->persist($role);

            // Roles Enfants
            $this->createRecursiveRole($children_roles, $role, $manager, $position);
        }
    }

}
