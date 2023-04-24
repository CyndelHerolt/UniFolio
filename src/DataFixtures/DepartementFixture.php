<?php

namespace App\DataFixtures;

use App\Entity\Departement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DepartementFixture extends Fixture implements OrderedFixtureInterface
{

    public function getOrder()
    {
        return 11;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $departement1 = new Departement();
        $departement1->setLibelle('MMI')
            ->setCouleur('purple')
            ;
        $manager->persist($departement1);

        $departement2 = new Departement();
        $departement2->setLibelle('GEA')
            ->setCouleur('blue')
            ;
        $manager->persist($departement2);

//        $departement3 = new Departement();
//        $departement3->setLibelle('GMP')
//            ->setCouleur('green')
//            ;
//        $manager->persist($departement3);
//
//        $departement4 = new Departement();
//        $departement4->setLibelle('GEII')
//            ->setCouleur('yellow')
//            ;
//        $manager->persist($departement4);
//
//        $departement5 = new Departement();
//        $departement5->setLibelle('CJ')
//            ->setCouleur('red')
//            ;
//        $manager->persist($departement5);

        $manager->flush();
    }
}
