<?php

namespace App\DataFixtures;

use App\Entity\DocumentTransverse;
use App\Entity\DocumentType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DocumentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //documentType
        $document = new DocumentType();
        $document->setName(1);
        $document->setCode('GENERALE');
        $manager->persist($document);

        $document1 = new DocumentType();
        $document1->setName(2);
        $document1->setCode('QUALITE');
        $manager->persist($document1);

        $manager->flush();
    }
}
