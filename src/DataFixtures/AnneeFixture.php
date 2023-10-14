<?php
/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
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
        return 7;
    }

    public function load(ObjectManager $manager)
    {
        $diplome1 = $this->diplomeRepository->findOneBy(['sigle' => 'MMI']);
        $diplome2 = $this->diplomeRepository->findOneBy(['sigle' => 'MMI-Crea']);
        $diplome3 = $this->diplomeRepository->findOneBy(['sigle' => 'MMI-DWeb-DI']);
        $diplome4 = $this->diplomeRepository->findOneBy(['sigle' => 'MMI-DWeb-DI-FC']);
        $diplome5 = $this->diplomeRepository->findOneBy(['sigle' => 'MMI-Strat-UX-FC']);
        $diplome6 = $this->diplomeRepository->findOneBy(['sigle' => 'MMI-Strat-UX']);
        $diplome7 = $this->diplomeRepository->findOneBy(['sigle' => 'CJ']);
        $diplome8 = $this->diplomeRepository->findOneBy(['sigle' => 'CJ-AJ']);
        $diplome9 = $this->diplomeRepository->findOneBy(['sigle' => 'CJ-EA']);

        $annee1 = new Annee();
        $annee1->setLibelle('MMI 1')
            ->setId(1)
            ->setOrdre(1)
            ->setActif(1)
            ->setLibelleLong('Première année de B.U.T. MMI')
            ->setOptAlternance(0)
            ->setDiplome($diplome1);
        $manager->persist($annee1);

        $annee2 = new Annee();
        $annee2->setLibelle('MMI-Crea-2')
            ->setId(2)
            ->setOrdre(2)
            ->setActif(1)
            ->setLibelleLong('Deuxiéme année de B.U.T. MMI-parcours Création Numérique')
            ->setOptAlternance(1)
            ->setDiplome($diplome2);
        $manager->persist($annee2);

        $annee3 = new Annee();
        $annee3->setLibelle('MMI-Crea-3')
            ->setId(3)
            ->setOrdre(3)
            ->setActif(1)
            ->setLibelleLong('Troisième année de B.U.T. MMI-parcours Création Numérique')
            ->setOptAlternance(1)
            ->setDiplome($diplome2);
        $manager->persist($annee3);

        $annee4 = new Annee();
        $annee4->setLibelle('MMI-DWeb-DI-2')
            ->setId(4)
            ->setOrdre(2)
            ->setActif(1)
            ->setLibelleLong('Deuxiéme année de B.U.T. MMI-parcours Développement web et dispositifs interactifs')
            ->setOptAlternance(1)
            ->setDiplome($diplome3);
        $manager->persist($annee4);

        $annee5 = new Annee();
        $annee5->setLibelle('MMI-DWeb-DI-3')
            ->setId(5)
            ->setOrdre(3)
            ->setActif(1)
            ->setLibelleLong('Troisième année de B.U.T. MMI-parcours Développement web et dispositifs interactifs')
            ->setOptAlternance(1)
            ->setDiplome($diplome3);
        $manager->persist($annee5);

        $annee6 = new Annee();
        $annee6->setLibelle('MMI-DWeb-DI-FC-2')
            ->setId(6)
            ->setOrdre(2)
            ->setActif(1)
            ->setLibelleLong('Deuxiéme année de B.U.T. MMI-parcours Développement web et dispositifs interactifs en formation continue')
            ->setOptAlternance(0)
            ->setDiplome($diplome4);
        $manager->persist($annee6);

        $annee7 = new Annee();
        $annee7->setLibelle('MMI-DWeb-DI-FC-3')
            ->setId(7)
            ->setOrdre(3)
            ->setActif(1)
            ->setLibelleLong('Troisième année de B.U.T. MMI-parcours Développement web et dispositifs interactifs en formation continue')
            ->setOptAlternance(0)
            ->setDiplome($diplome4);
        $manager->persist($annee7);

        $annee8 = new Annee();
        $annee8->setLibelle('MMI-Strat-UX-2')
            ->setId(8)
            ->setOrdre(2)
            ->setActif(1)
            ->setLibelleLong('Deuxiéme année de B.U.T. MMI-parcours Stratégie de communication numérique et design d’expérience')
            ->setOptAlternance(1)
            ->setDiplome($diplome6);
        $manager->persist($annee8);

        $annee9 = new Annee();
        $annee9->setLibelle('MMI-Strat-UX-3')
            ->setId(9)
            ->setOrdre(3)
            ->setActif(1)
            ->setLibelleLong('Troisième année de B.U.T. MMI-parcours Stratégie de communication numérique et design d’expérience')
            ->setOptAlternance(1)
            ->setDiplome($diplome6);
        $manager->persist($annee9);

        $annee10 = new Annee();
        $annee10->setLibelle('MMI-Strat-UX-FC-2')
            ->setId(10)
            ->setOrdre(2)
            ->setActif(1)
            ->setLibelleLong('Deuxiéme année de B.U.T. MMI-parcours Stratégie de communication numérique et design d’expérience en formation continue')
            ->setOptAlternance(0)
            ->setDiplome($diplome5);
        $manager->persist($annee10);

        $annee11 = new Annee();
        $annee11->setLibelle('MMI-Strat-UX-FC-3')
            ->setId(11)
            ->setOrdre(3)
            ->setActif(1)
            ->setLibelleLong('Troisième année de B.U.T. MMI-parcours Stratégie de communication numérique et design d’expérience en formation continue')
            ->setOptAlternance(0)
            ->setDiplome($diplome5);
        $manager->persist($annee11);

        $annee12 = new Annee();
        $annee12->setLibelle('CJ 1')
            ->setId(12)
            ->setOrdre(1)
            ->setActif(1)
            ->setLibelleLong('Première année de B.U.T. Carrières Juridiques')
            ->setOptAlternance(0)
            ->setDiplome($diplome7);
        $manager->persist($annee12);

        $annee13 = new Annee();
        $annee13->setLibelle('CJ-AJ-2')
            ->setId(13)
            ->setOrdre(2)
            ->setActif(1)
            ->setLibelleLong('Deuxiéme année de B.U.T. Carrières Juridiques-parcours Administration et Justice')
            ->setOptAlternance(0)
            ->setDiplome($diplome8);
        $manager->persist($annee13);

        $annee14 = new Annee();
        $annee14->setLibelle('CJ-AJ-3')
            ->setId(14)
            ->setOrdre(3)
            ->setActif(1)
            ->setLibelleLong('Troisième année de B.U.T. Carrières Juridiques-parcours Administration et Justice')
            ->setOptAlternance(0)
            ->setDiplome($diplome8);
        $manager->persist($annee14);

        $annee15 = new Annee();
        $annee15->setLibelle('CJ-EA-2')
            ->setId(15)
            ->setOrdre(2)
            ->setActif(1)
            ->setLibelleLong('Deuxiéme année de B.U.T. Carrières Juridiques-parcours Entreprise et Administration')
            ->setOptAlternance(0)
            ->setDiplome($diplome9);
        $manager->persist($annee15);

        $annee16 = new Annee();
        $annee16->setLibelle('CJ-EA-3')
            ->setId(16)
            ->setOrdre(3)
            ->setActif(1)
            ->setLibelleLong('Troisième année de B.U.T. Carrières Juridiques-parcours Entreprise et Administration')
            ->setOptAlternance(0)
            ->setDiplome($diplome9);
        $manager->persist($annee16);

        $manager->flush();
    }
}
