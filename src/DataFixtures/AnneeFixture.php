<?php

namespace App\DataFixtures;

use App\Entity\Annee;
use App\Repository\DiplomeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AnneeFixture extends Fixture implements OrderedFixtureInterface
{

    private $diplomeRepository;

    public function __construct(DiplomeRepository $diplomeRepository)
    {
        $this->diplomeRepository = $diplomeRepository;
    }

    public function getOrder()
    {
        return 3;
    }

    public function load(ObjectManager $manager)
    {
        $diplome1 = $this->diplomeRepository->findOneBy(['sigle' => 'MMI']);
        $diplome2 = $this->diplomeRepository->findOneBy(['sigle' => 'MMI-Crea']);
        $diplome3 = $this->diplomeRepository->findOneBy(['sigle' => 'MMI-DWeb-DI']);
        $diplome4 = $this->diplomeRepository->findOneBy(['sigle' => 'MMI-DWeb-DI-FC']);
        $diplome5 = $this->diplomeRepository->findOneBy(['sigle' => 'MMI-Strat-UX-FC']);

        $annee1 = new Annee();
        $annee1->setLibelle('MMI-1')
            ->setId(1)
            ->setOrdre(1)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 1ere année - MMI')
            ->setOptAlternance(0)
            ->setDiplome($diplome1);
        $manager->persist($annee1);

        $annee2 = new Annee();
        $annee2->setLibelle('MMI-2-Crea')
            ->setId(2)
            ->setOrdre(2)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 2eme année - parcours Création Numérique')
            ->setOptAlternance(0)
            ->setDiplome($diplome2);
        $manager->persist($annee2);

        $annee3 = new Annee();
        $annee3->setLibelle('MMI-2-DWeb-DI')
            ->setId(3)
            ->setOrdre(2)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 2eme année - parcours Développement Web et Dispositifs Interactifs')
            ->setOptAlternance(0)
            ->setDiplome($diplome3);
        $manager->persist($annee3);

        $annee4 = new Annee();
        $annee4->setLibelle('MMI-2-DWeb-DI-FC')
            ->setId(4)
            ->setOrdre(2)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 2eme année - parcours Développement Web et Dispositifs Interactifs - Formation Continue')
            ->setOptAlternance(1)
            ->setDiplome($diplome4);
        $manager->persist($annee4);

        $annee5 = new Annee();
        $annee5->setLibelle('MMI-2-Strat-UX-FC')
            ->setId(5)
            ->setOrdre(2)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 2eme année - parcours Stratégie de communication et Expérience Utilisateur - Formation Continue')
            ->setOptAlternance(1)
            ->setDiplome($diplome5);
        $manager->persist($annee5);

        $annee6 = new Annee();
        $annee6->setLibelle('MMI-3-Crea')
            ->setId(6)
            ->setOrdre(3)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 3eme année - parcours Création Numérique')
            ->setOptAlternance(1)
            ->setDiplome($diplome2);
        $manager->persist($annee6);

        $annee7 = new Annee();
        $annee7->setLibelle('MMI-3-DWeb-DI')
            ->setId(7)
            ->setOrdre(3)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 3eme année - parcours Développement Web et Dispositifs Interactifs')
            ->setOptAlternance(0)
            ->setDiplome($diplome3);
        $manager->persist($annee7);

        $annee8 = new Annee();
        $annee8->setLibelle('MMI-3-DWeb-DI-FC')
            ->setId(8)
            ->setOrdre(3)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 3eme année - parcours Développement Web et Dispositifs Interactifs - Formation Continue')
            ->setOptAlternance(1)
            ->setDiplome($diplome4);
        $manager->persist($annee8);

        $annee9 = new Annee();
        $annee9->setLibelle('MMI-3-Strat-UX-FC')
            ->setId(9)
            ->setOrdre(3)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 3eme année - parcours Stratégie de communication et Expérience Utilisateur - Formation Continue')
            ->setOptAlternance(1)
            ->setDiplome($diplome5);
        $manager->persist($annee9);

        $manager->flush();
    }
}
