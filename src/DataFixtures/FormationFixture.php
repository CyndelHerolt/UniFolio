<?php

namespace App\DataFixtures;

use App\Entity\Formation;
use App\Repository\CvRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FormationFixture extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $formation1 = new Formation();
        $formation1->setDiplome('BUT MMI')
            ->setEtablissement('IUT de Troyes')
            ->setDateDebut('2019-01-01')
            ->setDateFin('2020-01-01')
            ->setActivite(['Développement web', 'Développement Unity', 'Modélisation 3D', 'Communication']);

        $manager->persist($formation1);

        $formation2 = new Formation();
        $formation2->setDiplome('Passerelle vers un BUT')
            ->setEtablissement('IUT de Troyes')
            ->setDateDebut('2020-01-01')
            ->setDateFin('2021-01-01')
            ->setActivite(['Droit', 'Gestion de projet', 'Comptabilité']);

        $manager->persist($formation2);

        $formation3 = new Formation();
        $formation3->setDiplome('Diplome d\'accès aux études universitaires')
            ->setEtablissement('IUT de Troyes')
            ->setDateDebut('2021-01-01')
            ->setDateFin('2022-01-01')
            ->setActivite(['Pas grand chose...']);

        $manager->persist($formation3);

        $formation4 = new Formation();
        $formation4->setDiplome('BEP Vente Métiers de la Relation Client')
            ->setEtablissement('Lycée professionnel Gabriel Voisin')
            ->setDateDebut('2022-01-01')
            ->setDateFin('2023-01-01')
            ->setActivite(['Entretiens de vente', 'Economie', 'Prévention santé environnement']);

        $manager->persist($formation4);

        $manager->flush();
    }

    public function getOrder()
    {
        return 7;
    }
}
