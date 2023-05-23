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
        return 5;
    }
    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {

        $typeGroupe1 = new TypeGroupe();
        $typeGroupe1->setId(1);
        $typeGroupe1->setLibelle('CM');
        $typeGroupe1->setOrdreSemestre(1);
        $manager->persist($typeGroupe1);

        $typeGroupe2 = new TypeGroupe();
        $typeGroupe2->setId(2);
        $typeGroupe2->setLibelle('TD');
        $typeGroupe2->setOrdreSemestre(2);
        $manager->persist($typeGroupe2);

        $typeGroupe3 = new TypeGroupe();
        $typeGroupe3->setId(3);
        $typeGroupe3->setLibelle('TP');
        $typeGroupe3->setOrdreSemestre(3);
        $manager->persist($typeGroupe3);

        $manager->flush();
    }
}
