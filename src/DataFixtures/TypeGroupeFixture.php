<?php

namespace App\DataFixtures;

use App\Entity\TypeGroupe;
use App\Repository\SemestreRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TypeGroupeFixture extends Fixture implements OrderedFixtureInterface
{

    public function getOrder()
    {
        return 9;
    }
    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {

        $typeGroupe1 = new TypeGroupe();
        $typeGroupe1->setLibelle('CM');
        $manager->persist($typeGroupe1);

        $typeGroupe2 = new TypeGroupe();
        $typeGroupe2->setLibelle('TD');
        $manager->persist($typeGroupe2);

        $typeGroupe3 = new TypeGroupe();
        $typeGroupe3->setLibelle('TP');
        $manager->persist($typeGroupe3);

        $manager->flush();
    }
}
