<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
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

    public function __construct(AnneeRepository $anneeRepository)
    {
        $this->anneeRepository = $anneeRepository;
    }

    public function getOrder(): int
    {
        return 8;
    }

    public function load(ObjectManager $manager)
    {
        $annee1 = $this->anneeRepository->findOneBy(['libelle' => 'MMI 1']);
        $annee2 = $this->anneeRepository->findOneBy(['libelle' => 'MMI-Crea-2']);
        $annee3 = $this->anneeRepository->findOneBy(['libelle' => 'MMI-Crea-3']);
        $annee4 = $this->anneeRepository->findOneBy(['libelle' => 'MMI-DWeb-DI-2']);
        $annee5 = $this->anneeRepository->findOneBy(['libelle' => 'MMI-DWeb-DI-3']);
        $annee6 = $this->anneeRepository->findOneBy(['libelle' => 'MMI-DWeb-DI-FC-2']);
        $annee7 = $this->anneeRepository->findOneBy(['libelle' => 'MMI-DWeb-DI-FC-3']);
        $annee8 = $this->anneeRepository->findOneBy(['libelle' => 'MMI-Strat-UX-2']);
        $annee9 = $this->anneeRepository->findOneBy(['libelle' => 'MMI-Strat-UX-3']);
        $annee10 = $this->anneeRepository->findOneBy(['libelle' => 'MMI-Strat-UX-FC-2']);
        $annee11 = $this->anneeRepository->findOneBy(['libelle' => 'MMI-Strat-UX-FC-3']);
        $annee12 = $this->anneeRepository->findOneBy(['libelle' => 'CJ 1']);
        $annee13 = $this->anneeRepository->findOneBy(['libelle' => 'CJ-AJ-2']);
        $annee14 = $this->anneeRepository->findOneBy(['libelle' => 'CJ-AJ-3']);
        $annee15 = $this->anneeRepository->findOneBy(['libelle' => 'CJ-EA-2']);
        $annee16 = $this->anneeRepository->findOneBy(['libelle' => 'CJ-EA-3']);

        $semestreCount = 0;
        for ($i = 1; $i <= 16; $i++) { // We start from 1 (annee1) and go through to the last annee
            $currentAnnee = ${'annee' . $i};
            $semestreLibelles = (strpos($currentAnnee->getLibelle(), '1') !== false) ? ['S1', 'S2'] : ((strpos($currentAnnee->getLibelle(), '2') !== false) ? ['S3', 'S4'] : ['S5', 'S6']);

            foreach ($semestreLibelles as $j => $libelle) {
                $semestreCount++;
                $currentSemester = new Semestre();
                $currentSemester->setId($semestreCount)
                    ->setLibelle($libelle)
                    ->setOrdreAnnee($j + 1)
                    ->setActif($j % 2)
                    ->setAnnee($currentAnnee);
                $manager->persist($currentSemester);
            }
        }
        // Flushing all changes to the database
        $manager->flush();
    }
}
