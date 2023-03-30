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
        $diplome1 = $this->diplomeRepository->findOneBy(['libelle' => 'Métiers du Multimédia et de l\'Internet']);
        $diplome2 = $this->diplomeRepository->findOneBy(['libelle' => 'Gestion des Entreprises et des Administrations']);
        $diplome3 = $this->diplomeRepository->findOneBy(['libelle' => 'Genie Mécanique et Productique']);
        $diplome4 = $this->diplomeRepository->findOneBy(['libelle' => 'Genie Electrique et Informatique Industrielle']);
        $diplome5 = $this->diplomeRepository->findOneBy(['libelle' => 'Carrières Juridiques']);

        $semestre1 = new Annee();
        $semestre1->setLibelle('BUT1')
            ->setOrdre(1)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 1ere année')
            ->setOptAlternance(0)
            ->setDiplomes($diplome1);
        $manager->persist($semestre1);

        $semestre2 = new Annee();
        $semestre2->setLibelle('BUT2')
            ->setOrdre(2)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 2eme année')
            ->setOptAlternance(1)
            ->setDiplomes($diplome2);
        $manager->persist($semestre2);

        $semestre3 = new Annee();
        $semestre3->setLibelle('BUT3')
            ->setOrdre(3)
            ->setActif(1)
            ->setLibelleLong('Bachelor Universitaire de Technologie 3eme année')
            ->setOptAlternance(1)
            ->setDiplomes($diplome3);
        $manager->persist($semestre3);

        $manager->flush();
    }
}