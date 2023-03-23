<?php

namespace App\DataFixtures;

use App\Entity\Experience;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ExperienceFixture extends Fixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $experience1 = new Experience();
        $experience1->setPoste('Développeur web')
            ->setEntreprise('Société 1')
            ->setDateDebut('2019-01-01')
            ->setDateFin('2020-01-01')
            ->setActivite(['Développement du site web', 'Maintenance des outils internes']);

        $manager->persist($experience1);

        $experience2 = new Experience();
        $experience2->setPoste('Chargé de clientèle')
            ->setEntreprise('Société 2')
            ->setDateDebut('2020-01-01')
            ->setDateFin('2021-01-01')
            ->setActivite(['Conseils clients', 'Accompagnement lors des procédures']);

        $manager->persist($experience2);

        $experience3 = new Experience();
        $experience3->setPoste('Stagiaire Assistant commercial')
            ->setEntreprise('Société 3')
            ->setDateDebut('2021-01-01')
            ->setDateFin('2022-01-01')
            ->setActivite(['Réalisation d\'une campagne de prospection', 'Organisation d\'un évènement produit']);

        $manager->persist($experience3);

        $experience4 = new Experience();
        $experience4->setPoste('Ouvrier BTP multiservices')
            ->setEntreprise('Société 4')
            ->setDateDebut('2022-01-01')
            ->setDateFin('2023-01-01')
            ->setActivite(['Pose de carrelage', 'Pose de parquet', 'Pose de placo']);

        $manager->persist($experience4);

        $manager->flush();
    }

    public function getOrder()
    {
        return 6;
    }
}
