<?php

namespace App\DataFixtures;

use App\Entity\Diplome;
use App\Repository\ApcParcoursRepository;
use App\Repository\DepartementRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DiplomeFixture extends Fixture implements OrderedFixtureInterface
{

    private $departementRepository;
    private $apcParcoursRepository;

    public function __construct(
        DepartementRepository $departementRepository,
        ApcParcoursRepository $apcParcoursRepository,
    )
    {
        $this->departementRepository = $departementRepository;
        $this->apcParcoursRepository = $apcParcoursRepository;
    }

    public function getOrder()
    {
        return 6;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $departement1 = $this->departementRepository->findOneBy(['libelle' => 'MMI']);
        $departement2 = $this->departementRepository->findOneBy(['libelle' => 'CJ']);

        $parcours1 = $this->apcParcoursRepository->findOneBy(['code' => 'Strat-UX']);
        $parcours2 = $this->apcParcoursRepository->findOneBy(['code' => 'Strat-UXFC']);
        $parcours3 = $this->apcParcoursRepository->findOneBy(['code' => 'Crea']);
        $parcours4 = $this->apcParcoursRepository->findOneBy(['code' => 'DWeb-DI']);
        $parcours5 = $this->apcParcoursRepository->findOneBy(['code' => 'DWeb-DI-FC']);
        $parcours6 = $this->apcParcoursRepository->findOneBy(['code' => 'AJ']);
        $parcours7 = $this->apcParcoursRepository->findOneBy(['code' => 'PF']);
        $parcours8 = $this->apcParcoursRepository->findOneBy(['code' => 'EA']);


        $diplome1 = new Diplome();
        $diplome1->setLibelle('Métiers du Multimédia et de l\'Internet')
            ->setId(1)
            ->setSigle('MMI')
            ->setDepartement($departement1);
        $manager->persist($diplome1);

        $diplome2 = new Diplome();
        $diplome2->setLibelle('Métiers du Multimédia et de l\'Internet Parcours : Stratégie de communication numérique et design d’expérience FC')
            ->setId(2)
            ->setSigle('MMI-Strat-UX-FC')
            ->setApcParcours($parcours2)
            ->setDepartement($departement2);
        $manager->persist($diplome2);

        $diplome3 = new Diplome();
        $diplome3->setLibelle('Métiers du Multimédia et de l\'Internet Parcours : Développement web et dispositifs interactifs FC')
            ->setId(3)
            ->setSigle('MMI-DWeb-Di-FC')
            ->setApcParcours($parcours5)
            ->setDepartement($departement1);
        $manager->persist($diplome3);

        $diplome4 = new Diplome();
        $diplome4->setLibelle('Métiers du Multimédia et de l\'Internet Parcours : Développement web et dispositifs interactifs')
            ->setId(4)
            ->setSigle('MMI-DWeb-Di')
            ->setApcParcours($parcours4)
            ->setDepartement($departement1);
        $manager->persist($diplome4);

        $diplome5 = new Diplome();
        $diplome5->setLibelle('Métiers du Multimédia et de l\'Internet Parcours : Stratégie de communication numérique et design d’expérience')
            ->setId(5)
            ->setSigle('MMI-Strat-UX')
            ->setApcParcours($parcours1)
            ->setDepartement($departement1);
        $manager->persist($diplome5);

        $diplome6 = new Diplome();
        $diplome6->setLibelle('Métiers du Multimédia et de l\'Internet Parcours : Création Numérique')
            ->setId(6)
            ->setSigle('MMI-Crea')
            ->setApcParcours($parcours3)
            ->setDepartement($departement1);
        $manager->persist($diplome6);

        $diplome7 = new Diplome();
        $diplome7->setLibelle('Carrières Juridiques')
            ->setId(7)
            ->setSigle('CJ')
            ->setDepartement($departement2);
        $manager->persist($diplome7);

        $diplome8 = new Diplome();
        $diplome8->setLibelle('Carrières Juridiques Parcours : Administration et Justice')
            ->setId(8)
            ->setSigle('CJ-AJ')
            ->setApcParcours($parcours6)
            ->setDepartement($departement2);
        $manager->persist($diplome8);

        $diplome9 = new Diplome();
        $diplome9->setLibelle('Carrières Juridiques Parcours : Entreprise et Association')
            ->setId(9)
            ->setSigle('CJ-EA')
            ->setApcParcours($parcours8)
            ->setDepartement($departement2);
        $manager->persist($diplome9);

        $manager->flush();
    }
}
