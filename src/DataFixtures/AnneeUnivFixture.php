<?php

namespace App\DataFixtures;

use App\Entity\AnneeUniversitaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class AnneeUnivFixture extends Fixture implements OrderedFixtureInterface
{

    public function getOrder()
    {
        // Retourner un entier qui indique l'ordre de chargement des fixations
        return 8;
    }

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
