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
        return 13;
    }

    public function load(ObjectManager $manager)
    {
//TODO: Repréciser les diplomes
        $diplome1 = $this->diplomeRepository->findOneBy(['sigle' => 'MMI']);
        $diplome2 = $this->diplomeRepository->findOneBy(['sigle' => 'MMI-Crea']);
        $diplome3 = $this->diplomeRepository->findOneBy(['sigle' => 'MMI-DWeb-DI']);
        $diplome4 = $this->diplomeRepository->findOneBy(['sigle' => 'MMI-DWeb-DI-FC']);
        $diplome5 = $this->diplomeRepository->findOneBy(['sigle' => 'MMI-Strat-UX-FC']);
        $diplome6 = $this->diplomeRepository->findOneBy(['sigle' => 'GEA']);
        $diplome7 = $this->diplomeRepository->findOneBy(['sigle' => 'GEA-GC2F']);
        $diplome8 = $this->diplomeRepository->findOneBy(['sigle' => 'GEA-GEMA']);
        $diplome9 = $this->diplomeRepository->findOneBy(['sigle' => 'GEA-GEMA-FC']);
        $diplome10 = $this->diplomeRepository->findOneBy(['sigle' => 'GEA-GRH']);

        $annee1 = new Annee();
        $annee1->setLibelle('MMI-1')
            ->setOrdre(1)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 1ere année - MMI')
            ->setOptAlternance(0)
            ->setDiplome($diplome1);
        $manager->persist($annee1);

        $annee2 = new Annee();
        $annee2->setLibelle('MMI-2-Crea')
            ->setOrdre(2)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 2eme année - parcours Création Numérique')
            ->setOptAlternance(0)
            ->setDiplome($diplome2);
        $manager->persist($annee2);

        $annee3 = new Annee();
        $annee3->setLibelle('MMI-2-DWeb-DI')
            ->setOrdre(2)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 2eme année - parcours Développement Web et Dispositifs Interactifs')
            ->setOptAlternance(0)
            ->setDiplome($diplome3);
        $manager->persist($annee3);

        $annee4 = new Annee();
        $annee4->setLibelle('MMI-2-DWeb-DI-FC')
            ->setOrdre(2)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 2eme année - parcours Développement Web et Dispositifs Interactifs - Formation Continue')
            ->setOptAlternance(1)
            ->setDiplome($diplome4);
        $manager->persist($annee4);

        $annee5 = new Annee();
        $annee5->setLibelle('MMI-2-Strat-UX-FC')
            ->setOrdre(2)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 2eme année - parcours Stratégie de communication et Expérience Utilisateur - Formation Continue')
            ->setOptAlternance(1)
            ->setDiplome($diplome5);
        $manager->persist($annee5);

        $annee6 = new Annee();
        $annee6->setLibelle('MMI-3-Crea')
            ->setOrdre(3)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 3eme année - parcours Création Numérique')
            ->setOptAlternance(1)
            ->setDiplome($diplome2);
        $manager->persist($annee6);

        $annee7 = new Annee();
        $annee7->setLibelle('MMI-3-DWeb-DI')
            ->setOrdre(3)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 3eme année - parcours Développement Web et Dispositifs Interactifs')
            ->setOptAlternance(0)
            ->setDiplome($diplome3);
        $manager->persist($annee7);

        $annee8 = new Annee();
        $annee8->setLibelle('MMI-3-DWeb-DI-FC')
            ->setOrdre(3)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 3eme année - parcours Développement Web et Dispositifs Interactifs - Formation Continue')
            ->setOptAlternance(1)
            ->setDiplome($diplome4);
        $manager->persist($annee8);

        $annee9 = new Annee();
        $annee9->setLibelle('MMI-3-Strat-UX-FC')
            ->setOrdre(3)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 3eme année - parcours Stratégie de communication et Expérience Utilisateur - Formation Continue')
            ->setOptAlternance(1)
            ->setDiplome($diplome5);
        $manager->persist($annee9);

        $annee10 = new Annee();
        $annee10->setLibelle('GEA-1')
            ->setOrdre(1)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 1ere année - Gestion des Entreprises et des Administrations')
            ->setOptAlternance(0)
            ->setDiplome($diplome6);
        $manager->persist($annee10);

        $annee11 = new Annee();
        $annee11->setLibelle('GEA-2-GC2F')
            ->setOrdre(2)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 2eme année - parcours Gestion Comptable et Financière')
            ->setOptAlternance(0)
            ->setDiplome($diplome7);
        $manager->persist($annee11);

        $annee12 = new Annee();
        $annee12->setLibelle('GEA-2-GEMA')
            ->setOrdre(2)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 2eme année - parcours Gestion des Entreprises et des Administrations')
            ->setOptAlternance(0)
            ->setDiplome($diplome8);
        $manager->persist($annee12);

        $annee13 = new Annee();
        $annee13->setLibelle('GEA-2-GEMA-FC')
            ->setOrdre(2)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 2eme année - parcours Gestion des Entreprises et des Administrations - Formation Continue')
            ->setOptAlternance(1)
            ->setDiplome($diplome9);
        $manager->persist($annee13);

        $annee14 = new Annee();
        $annee14->setLibelle('GEA-2-GRH')
            ->setOrdre(2)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 3eme année - parcours Gestion Comptable et Financière')
            ->setOptAlternance(0)
            ->setDiplome($diplome10);
        $manager->persist($annee14);

        $annee15 = new Annee();
        $annee15->setLibelle('GEA-3-GC2F')
            ->setOrdre(3)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 3eme année - parcours Gestion Comptable et Financière')
            ->setOptAlternance(0)
            ->setDiplome($diplome7);
        $manager->persist($annee15);

        $annee16 = new Annee();
        $annee16->setLibelle('GEA-3-GEMA')
            ->setOrdre(3)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 3eme année - parcours Gestion des Entreprises et des Administrations')
            ->setOptAlternance(0)
            ->setDiplome($diplome8);
        $manager->persist($annee16);

        $annee17 = new Annee();
        $annee17->setLibelle('GEA-3-GRH')
            ->setOrdre(3)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 3eme année - parcours Gestion des Entreprises et des Administrations')
            ->setOptAlternance(0)
            ->setDiplome($diplome10);
        $manager->persist($annee17);

        $manager->flush();
    }
}
