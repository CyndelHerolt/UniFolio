<?php

namespace App\DataFixtures;

use App\Entity\Diplome;
use App\Repository\DepartementRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DiplomeFixture extends Fixture implements OrderedFixtureInterface
{

    private $departementRepository;

    public function __construct(DepartementRepository $departementRepository)
    {
        $this->departementRepository = $departementRepository;
    }

    public function getOrder()
    {
        return 12;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $departement1 = $this->departementRepository->findOneBy(['libelle' => 'MMI']);
        $departement2 = $this->departementRepository->findOneBy(['libelle' => 'GEA']);
//        $departement3 = $this->departementRepository->findOneBy(['libelle' => 'GMP']);
//        $departement4 = $this->departementRepository->findOneBy(['libelle' => 'GEII']);
//        $departement5 = $this->departementRepository->findOneBy(['libelle' => 'CJ']);

        $diplome1 = new Diplome();
        $diplome1->setLibelle('Métiers du Multimédia et de l\'Internet Parcours : Création Numérique')
            ->setSigle('MMI-Crea')
            ->addDepartementId($departement1);
        $manager->persist($diplome1);

        $diplome2 = new Diplome();
        $diplome2->setLibelle('Métiers du Multimédia et de l\'Internet Parcours : Développement web et dispositifs interactifs')
            ->setSigle('MMI-DWeb-DI')
            ->addDepartementId($departement1);
        $manager->persist($diplome2);

        $diplome3 = new Diplome();
        $diplome3->setLibelle('Métiers du Multimédia et de l\'Internet Parcours : Développement web et dispositifs interactifs FC')
            ->setSigle('MMI-DWeb-DI-FC')
            ->addDepartementId($departement1);
        $manager->persist($diplome3);

        $diplome4 = new Diplome();
        $diplome4->setLibelle('Métiers du Multimédia et de l\'Internet Parcours : Stratégie de communication numérique et design d’expérience FC')
            ->setSigle('MMI-Strat-UX-FC')
            ->addDepartementId($departement1);
        $manager->persist($diplome4);

        $diplome5 = new Diplome();
        $diplome5->setLibelle('Métiers du Multimédia et de l\'Internet')
            ->setSigle('MMI')
            ->addDepartementId($departement1);
        $manager->persist($diplome5);

        $diplome6 = new Diplome();
        $diplome6->setLibelle('Gestion des Entreprises et des Administrations')
            ->setSigle('GEA')
            ->addDepartementId($departement2);
        $manager->persist($diplome6);

        $diplome7 = new Diplome();
        $diplome7->setLibelle('Gestion des Entreprises et des Administrations Parcours : Gestion comptable fiscale et financière ')
            ->setSigle('GEA-GC2F')
            ->addDepartementId($departement2);
        $manager->persist($diplome7);

        $diplome8 = new Diplome();
        $diplome8->setLibelle('Gestion des Entreprises et des Administrations Parcours : Gestion entrepreneuriat et management des activités')
            ->setSigle('GEA-GEMA')
            ->addDepartementId($departement2);
        $manager->persist($diplome8);

        $diplome9 = new Diplome();
        $diplome9->setLibelle('Gestion des Entreprises et des Administrations Parcours : Gestion entrepreneuriat et management des activités FC')
            ->setSigle('GEA-GEMA-FC')
            ->addDepartementId($departement2);
        $manager->persist($diplome9);

        $diplome10 = new Diplome();
        $diplome10->setLibelle('Gestion des Entreprises et des Administrations Parcours : Gestion des ressources humaines')
            ->setSigle('GEA-GRH')
            ->addDepartementId($departement2);
        $manager->persist($diplome10);

//        $diplome2 = new Diplome();
//        $diplome2->setLibelle('Gestion des Entreprises et des Administrations')
//            ->setSigle('GEA')
//            ->addDepartementId($departement2);
//        $manager->persist($diplome2);
//
//        $diplome3 = new Diplome();
//        $diplome3->setLibelle('Genie Mécanique et Productique')
//            ->setSigle('GMP')
//            ->addDepartementId($departement3);
//        $manager->persist($diplome3);
//
//        $diplome4 = new Diplome();
//        $diplome4->setLibelle('Genie Electrique et Informatique Industrielle')
//            ->setSigle('GEII')
//            ->addDepartementId($departement4);
//        $manager->persist($diplome4);
//
//        $diplome5 = new Diplome();
//        $diplome5->setLibelle('Carrières Juridiques')
//            ->setSigle('CJ')
//            ->addDepartementId($departement5);
//        $manager->persist($diplome5);

        $manager->flush();
    }
}
