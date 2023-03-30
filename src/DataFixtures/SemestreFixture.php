<?php

namespace App\DataFixtures;

use App\Entity\Semestre;
use App\Repository\AnneeRepository;
use App\Repository\TypeGroupeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SemestreFixture
{
    private $anneeRepository;

    public function __construct(AnneeRepository $anneeRepository)
    {
        $this->anneeRepository = $anneeRepository;
    }

    public function getOrder()
    {
        return 14;
    }

    public function load(ObjectManager $manager)
    {
        $annee1 = $this->anneeRepository->findOneBy(['libelle' => 'BUT1']);
        $annee2 = $this->anneeRepository->findOneBy(['libelle' => 'BUT2']);
        $annee3 = $this->anneeRepository->findOneBy(['libelle' => 'BUT3']);


        $semestre1 = new Semestre();
        $semestre1->setLibelle('S1')
            ->setOrdreAnnee(1)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(true)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee1)
        ;
        $manager->persist($semestre1);

        $semestre2 = new Semestre();
        $semestre2->setLibelle('S2')
            ->setOrdreAnnee(2)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(true)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee1);
        $manager->persist($semestre2);

        $semestre3 = new Semestre();
        $semestre3->setLibelle('S3')
            ->setOrdreAnnee(1)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(true)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee2);
        $manager->persist($semestre3);

        $semestre4 = new Semestre();
        $semestre4->setLibelle('S4')
            ->setOrdreAnnee(2)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(true)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee2);
        $manager->persist($semestre4);

        $semestre5 = new Semestre();
        $semestre5->setLibelle('S5')
            ->setOrdreAnnee(1)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(true)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee3);
        $manager->persist($semestre5);

        $semestre6 = new Semestre();
        $semestre6->setLibelle('S6')
            ->setOrdreAnnee(2)
            ->setDateCreation(new \DateTime('now'))
            ->setDateModification(new \DateTime('now'))
            ->setActif(true)
            ->setNbGroupesCm(1)
            ->setNbGroupesTd(4)
            ->setNbGroupesTp(8)
            ->setAnnee($annee3);
        $manager->persist($semestre6);

        $manager->flush();
    }

}