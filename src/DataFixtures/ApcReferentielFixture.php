<?php

namespace App\DataFixtures;

use App\Entity\ApcReferentiel;
use App\Repository\DepartementRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ApcReferentielFixture extends Fixture implements OrderedFixtureInterface
{
    private $departementRepository;

    public function __construct(DepartementRepository $departementRepository)
    {
        $this->departementRepository = $departementRepository;
    }

    public function getOrder()
    {
        return 2;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $departement1 = $this->departementRepository->findOneBy(['libelle' => 'MMI']);
        $departement2 = $this->departementRepository->findOneBy(['libelle' => 'CJ']);

        $apcReferentiel1 = new ApcReferentiel();
        $apcReferentiel1->setId(1)
            ->setLibelle('Référentiel de compétences du B.U.T. MMI')
            ->setDescription('Référentiel de compétences du B.U.T. MMI Version 2022')
            ->setAnneePublication(2022)
            ->setDepartement($departement1)
            ;
        $manager->persist($apcReferentiel1);

        $apcReferentiel2 = new ApcReferentiel();
        $apcReferentiel2->setId(2)
            ->setLibelle('Référentiel de compétences du B.U.T. CJ')
            ->setDescription('Référentiel de compétences du B.U.T. CJ Version 2022')
            ->setAnneePublication(2022)
            ->setDepartement($departement2)
            ;
        $manager->persist($apcReferentiel2);

        $manager->flush();
    }
}