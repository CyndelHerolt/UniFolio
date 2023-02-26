<?php

namespace App\DataFixtures;

use App\Entity\AnneeUniversitaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AnneeUnivFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $anneeUniv = new AnneeUniversitaire();

        $anneeUniv->setLibelle('2020-2021')
            ->setActive(true)
            ->setAnnee(2020);

        $manager->persist($anneeUniv);
        $manager->flush();
    }
}
