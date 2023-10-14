<?php
/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\DataFixtures;

use App\Entity\Departement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DepartementFixture extends Fixture implements OrderedFixtureInterface
{

    public function getOrder()
    {
        return 1;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $departement1 = new Departement();
        $departement1->setLibelle('MMI')
            ->setId(1)
            ->setTelContact('03 25 42 71 29')
            ->setCouleur('#0096ff')
            ->setDescription('Le département  Métiers de l’Internet et du Multimédia  de l’IUT de Troyes (précédemment Services et Réseaux de Communication) forme les acteurs de l’Internet, des médias numériques, de la communication plurimédia, de la création.');
            ;
        $manager->persist($departement1);

        $departement2 = new Departement();
        $departement2->setLibelle('CJ')
            ->setId(2)
            ->setTelContact('03 25 42 46 00')
            ->setCouleur('#C40F48')
            ->setDescription('Le DUT Carrières juridiques est une formation technologique, pluridisciplinaire à dominante juridique, destinée à former des techniciens du droit dans des domaines variés. Les emplois auxquels prépare le diplôme constituent en effet un éventail diversifié de métiers : l’étudiant ne saurait donc réduire sa réflexion au seul domaine du droit.');
            ;
        $manager->persist($departement2);

        $departement3 = new Departement();
        $departement3->setId(3)
            ->setLibelle('Fixtures')
            ->setTelContact('00 00 00 00 00')
            ->setCouleur('#000000')
            ->setDescription('Fixtures');
        $manager->persist($departement3);

        $manager->flush();
    }
}
