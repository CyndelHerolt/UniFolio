<?php

namespace App\DataFixtures;

use App\Entity\Semestre;
use App\Repository\AnneeRepository;
use App\Repository\TypeGroupeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SemestreFixture extends Fixture implements OrderedFixtureInterface
{
    private $anneeRepository;
    private $typeGroupeRepository;

    public function __construct(AnneeRepository $anneeRepository, TypeGroupeRepository $typeGroupeRepository)
    {
        $this->anneeRepository = $anneeRepository;
        $this->typeGroupeRepository = $typeGroupeRepository;
    }

    public function getOrder()
    {
        return 14;
    }

    public function load(ObjectManager $manager)
    {
        $annee1 = $this->anneeRepository->findOneBy(['sigle' => 'MMI-1']);
        $annee2 = $this->anneeRepository->findOneBy(['sigle' => 'MMI-2-Crea']);
        $annee3 = $this->anneeRepository->findOneBy(['sigle' => 'MMI-2-DWeb-DI']);
        $annee4 = $this->anneeRepository->findOneBy(['sigle' => 'MMI-2-DWeb-DI-FC']);
        $annee5 = $this->anneeRepository->findOneBy(['sigle' => 'MMI-2-Strat-UX-FC']);
        $annee6 = $this->anneeRepository->findOneBy(['sigle' => 'MMI-3-Crea']);
        $annee7 = $this->anneeRepository->findOneBy(['sigle' => 'MMI-3-DWeb-DI']);
        $annee8 = $this->anneeRepository->findOneBy(['sigle' => 'MMI-3-DWeb-DI-FC']);
        $annee9 = $this->anneeRepository->findOneBy(['sigle' => 'MMI-3-Strat-UX-FC']);
        $annee10 = $this->anneeRepository->findOneBy(['sigle' => 'GEA-1']);
        $annee11 = $this->anneeRepository->findOneBy(['sigle' => 'GEA-2-GC2F']);
        $annee12 = $this->anneeRepository->findOneBy(['sigle' => 'GEA-2-GEMA']);
        $annee13 = $this->anneeRepository->findOneBy(['sigle' => 'GEA-2-GEMA-FC']);
        $annee14 = $this->anneeRepository->findOneBy(['sigle' => 'GEA-2-GRH']);
        $annee15 = $this->anneeRepository->findOneBy(['sigle' => 'GEA-3-GC2F']);
        $annee16 = $this->anneeRepository->findOneBy(['sigle' => 'GEA-3-GEMA']);
        $annee17 = $this->anneeRepository->findOneBy(['sigle' => 'GEA-3-GEMA-FC']);
        $annee18 = $this->anneeRepository->findOneBy(['sigle' => 'GEA-3-GRH']);

        $typeGroupe1 = $this->typeGroupeRepository->findOneBy(['libelle' => 'CM']);
        $typeGroupe2 = $this->typeGroupeRepository->findOneBy(['libelle' => 'TD']);
        $typeGroupe3 = $this->typeGroupeRepository->findOneBy(['libelle' => 'TP']);



        $semestre1 = new Semestre();
        $semestre1->setLibelle('S1-MMI')
            ->setOrdreAnnee(1)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(true)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee1)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3)
        ;
        $manager->persist($semestre1);

        $semestre2 = new Semestre();
        $semestre2->setLibelle('S2-MMI')
            ->setOrdreAnnee(2)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(false)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee1)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre2);

        $semestre3 = new Semestre();
        $semestre3->setLibelle('S3-Crea')
            ->setOrdreAnnee(1)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(true)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee2)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre3);

        $semestre4 = new Semestre();
        $semestre4->setLibelle('S4-Crea')
            ->setOrdreAnnee(2)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(false)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee2)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre4);

        $semestre5 = new Semestre();
        $semestre5->setLibelle('S3-DWeb-DI')
            ->setOrdreAnnee(1)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(true)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee3)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre5);

        $semestre6 = new Semestre();
        $semestre6->setLibelle('S4-DWeb-DI')
            ->setOrdreAnnee(2)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(false)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee3)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre6);

        $semestre7 = new Semestre();
        $semestre7->setLibelle('S3-DWeb-DI-FC')
            ->setOrdreAnnee(1)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(true)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee4)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre7);

        $semestre8 = new Semestre();
        $semestre8->setLibelle('S4-DWeb-DI-FC')
            ->setOrdreAnnee(2)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(false)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee4)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre8);

        $semestre9 = new Semestre();
        $semestre9->setLibelle('S3-Strat-UX-FC')
            ->setOrdreAnnee(1)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(true)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee5)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre9);

        $semestre10 = new Semestre();
        $semestre10->setLibelle('S4-Strat-UX-FC')
            ->setOrdreAnnee(2)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(false)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee5)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre10);

        $semestre11 = new Semestre();
        $semestre11->setLibelle('S5-Crea')
            ->setOrdreAnnee(1)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(true)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee6)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre11);

        $semestre12 = new Semestre();
        $semestre12->setLibelle('S6-Crea')
            ->setOrdreAnnee(2)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(false)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee6)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre12);

        $semestre13 = new Semestre();
        $semestre13->setLibelle('S5-DWeb-DI')
            ->setOrdreAnnee(1)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(true)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee7)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre13);

        $semestre14 = new Semestre();
        $semestre14->setLibelle('S6-DWeb-DI')
            ->setOrdreAnnee(2)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(false)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee7)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre14);

        $semestre15 = new Semestre();
        $semestre15->setLibelle('S5-DWeb-DI-FC')
            ->setOrdreAnnee(1)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(true)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee8)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre15);

        $semestre16 = new Semestre();
        $semestre16->setLibelle('S6-DWeb-DI-FC')
            ->setOrdreAnnee(2)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(false)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee8)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre16);

        $semestre17 = new Semestre();
        $semestre17->setLibelle('S5-Strat-UX-FC')
            ->setOrdreAnnee(1)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(true)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee9)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre17);

        $semestre18 = new Semestre();
        $semestre18->setLibelle('S6-Strat-UX-FC')
            ->setOrdreAnnee(2)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(false)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee9)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre18);

        $semestre19 = new Semestre();
        $semestre19->setLibelle('S1-GEA')
            ->setOrdreAnnee(1)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(true)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee10)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre19);

        $semestre20 = new Semestre();
        $semestre20->setLibelle('S2-GEA')
            ->setOrdreAnnee(2)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(false)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee10)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre20);

        $semestre21 = new Semestre();
        $semestre21->setLibelle('S3-GC2F')
            ->setOrdreAnnee(1)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(false)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee11)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre21);

        $semestre22 = new Semestre();
        $semestre22->setLibelle('S4-GC2F')
            ->setOrdreAnnee(2)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(false)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee11)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre22);

        $semestre23 = new Semestre();
        $semestre23->setLibelle('S3-GEMA')
            ->setOrdreAnnee(1)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(true)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee12)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre23);

        $semestre24 = new Semestre();
        $semestre24->setLibelle('S4-GEMA')
            ->setOrdreAnnee(2)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(false)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee12)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre24);

        $semestre25 = new Semestre();
        $semestre25->setLibelle('S3-GEMA-FC')
            ->setOrdreAnnee(1)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(true)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee13)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre25);

        $semestre26 = new Semestre();
        $semestre26->setLibelle('S4-GEMA-FC')
            ->setOrdreAnnee(2)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(false)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee13)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre26);

        $semestre27 = new Semestre();
        $semestre27->setLibelle('S3-GRH')
            ->setOrdreAnnee(1)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(true)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee14)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre27);

        $semestre28 = new Semestre();
        $semestre28->setLibelle('S4-GRH')
            ->setOrdreAnnee(2)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(false)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee14)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre28);

        $semestre29 = new Semestre();
        $semestre29->setLibelle('S5-GC2F')
            ->setOrdreAnnee(1)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(true)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee15)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre29);

        $semestre30 = new Semestre();
        $semestre30->setLibelle('S6-GC2F')
            ->setOrdreAnnee(2)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(false)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee15)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre30);

        $semestre31 = new Semestre();
        $semestre31->setLibelle('S5-GEMA')
            ->setOrdreAnnee(1)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(true)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee16)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre31);

        $semestre32 = new Semestre();
        $semestre32->setLibelle('S6-GEMA')
            ->setOrdreAnnee(2)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(false)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee16)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre32);

        $semestre33 = new Semestre();
        $semestre33->setLibelle('S5-GEMA-FC')
            ->setOrdreAnnee(1)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(true)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee17)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre33);

        $semestre34 = new Semestre();
        $semestre34->setLibelle('S6-GEMA-FC')
            ->setOrdreAnnee(2)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(false)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee17)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre34);

        $semestre35 = new Semestre();
        $semestre35->setLibelle('S5-GRH')
            ->setOrdreAnnee(1)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(true)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee18)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre35);

        $semestre36 = new Semestre();
        $semestre36->setLibelle('S6-GRH')
            ->setOrdreAnnee(2)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(false)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee18)
            ->addTypeGroupe($typeGroupe1)
            ->addTypeGroupe($typeGroupe2)
            ->addTypeGroupe($typeGroupe3);
        $manager->persist($semestre36);

        $manager->flush();
    }

}
