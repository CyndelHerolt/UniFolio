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
        return 1;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $departement1 = $this->departementRepository->findOneBy(['libelle' => 'MMI']);

        $diplome1 = new Diplome();
        $diplome1->setLibelle('Métiers du Multimédia et de l\'Internet Parcours : Création Numérique')
            ->setId(1)
            ->setSigle('MMI-Crea')
            ;
        $manager->persist($diplome1);

        $diplome2 = new Diplome();
        $diplome2->setLibelle('Métiers du Multimédia et de l\'Internet Parcours : Développement web et dispositifs interactifs')
            ->setId(2)
            ->setSigle('MMI-DWeb-DI')
            ;
        $manager->persist($diplome2);

        $diplome3 = new Diplome();
        $diplome3->setLibelle('Métiers du Multimédia et de l\'Internet Parcours : Développement web et dispositifs interactifs FC')
            ->setId(3)
            ->setSigle('MMI-DWeb-DI-FC')
            ;
        $manager->persist($diplome3);

        $diplome4 = new Diplome();
        $diplome4->setLibelle('Métiers du Multimédia et de l\'Internet Parcours : Stratégie de communication numérique et design d’expérience FC')
            ->setId(4)
            ->setSigle('MMI-Strat-UX-FC')
            ;
        $manager->persist($diplome4);

        $diplome5 = new Diplome();
        $diplome5->setLibelle('Métiers du Multimédia et de l\'Internet')
            ->setId(5)
            ->setSigle('MMI')
            ;
        $manager->persist($diplome5);


        $manager->flush();
    }
}
