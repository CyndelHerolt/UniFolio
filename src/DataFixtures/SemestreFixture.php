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
        return 7;
    }

    public function load(ObjectManager $manager)
    {
        $annee1 = $this->anneeRepository->findOneBy(['libelle' => 'MMI-1']);
        $annee2 = $this->anneeRepository->findOneBy(['libelle' => 'MMI-2-Crea']);
        $annee3 = $this->anneeRepository->findOneBy(['libelle' => 'MMI-2-DWeb-DI']);
        $annee4 = $this->anneeRepository->findOneBy(['libelle' => 'MMI-2-DWeb-DI-FC']);
        $annee5 = $this->anneeRepository->findOneBy(['libelle' => 'MMI-2-Strat-UX-FC']);
        $annee6 = $this->anneeRepository->findOneBy(['libelle' => 'MMI-3-Crea']);
        $annee7 = $this->anneeRepository->findOneBy(['libelle' => 'MMI-3-DWeb-DI']);
        $annee8 = $this->anneeRepository->findOneBy(['libelle' => 'MMI-3-DWeb-DI-FC']);
        $annee9 = $this->anneeRepository->findOneBy(['libelle' => 'MMI-3-Strat-UX-FC']);

        $typeGroupe1 = $this->typeGroupeRepository->findOneBy(['libelle' => 'CM']);
        $typeGroupe2 = $this->typeGroupeRepository->findOneBy(['libelle' => 'TD']);
        $typeGroupe3 = $this->typeGroupeRepository->findOneBy(['libelle' => 'TP']);



        $semestre1 = new Semestre();
        $semestre1->setLibelle('S1-MMI')
            ->setId(1)
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
            ->setId(2)
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
            ->setId(3)
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
            ->setId(4)
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
            ->setId(5)
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
            ->setId(6)
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
            ->setId(7)
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
            ->setId(8)
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
            ->setId(9)
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
            ->setId(10)
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
            ->setId(11)
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
            ->setId(12)
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
            ->setId(13)
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
            ->setId(14)
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
            ->setId(15)
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
            ->setId(16)
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
            ->setId(17)
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
            ->setId(18)
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

        $manager->flush();
    }

}
