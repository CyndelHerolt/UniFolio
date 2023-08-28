<?php

namespace App\DataFixtures;

use App\Entity\ApcParcours;
use App\Repository\ApcReferentielRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ApcParcoursFixture extends Fixture implements OrderedFixtureInterface
{
    private $apcReferentielRepository;

    public function __construct(ApcReferentielRepository $apcReferentielRepository)
    {
        $this->apcReferentielRepository = $apcReferentielRepository;
    }

    /**
     * @inheritDoc
     */
    public function getOrder()
    {
        return 5;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $referentiel1 = $this->apcReferentielRepository->findOneBy(['libelle' => 'Référentiel de compétences du B.U.T. MMI']);
        $referentiel2 = $this->apcReferentielRepository->findOneBy(['libelle' => 'Référentiel de compétences du B.U.T. CJ']);

        $parcours1 = new ApcParcours();
        $parcours1->setId(1)
            ->setLibelle('Stratégie de communication numérique et design d’expérience')
            ->setCode('Strat-UX')
            ->setActif(true)
            ->setApcReferentiel($referentiel1);
        $manager->persist($parcours1);

        $parcours2 = new ApcParcours();
        $parcours2->setId(2)
            ->setLibelle('Stratégie de communication numérique et design d’expérience')
            ->setCode('Strat-UXFC')
            ->setActif(true)
            ->setApcReferentiel($referentiel1);
        $manager->persist($parcours2);

        $parcours3 = new ApcParcours();
        $parcours3->setId(3)
            ->setLibelle('Création Numérique')
            ->setCode('Crea')
            ->setActif(true)
            ->setApcReferentiel($referentiel1);
        $manager->persist($parcours3);

        $parcours4 = new ApcParcours();
        $parcours4->setId(4)
            ->setLibelle('Développement web et dispositifs interactifs')
            ->setCode('DWeb-DI')
            ->setActif(true)
            ->setApcReferentiel($referentiel1);
        $manager->persist($parcours4);

        $parcours5 = new ApcParcours();
        $parcours5->setId(5)
            ->setLibelle('Développement web et dispositifs interactifs')
            ->setCode('DWeb-DI-FC')
            ->setActif(true)
            ->setApcReferentiel($referentiel1);
        $manager->persist($parcours5);

        $parcours6 = new ApcParcours();
        $parcours6->setId(6)
            ->setLibelle('Administration et Justice')
            ->setCode('AJ')
            ->setActif(true)
            ->setApcReferentiel($referentiel2);
        $manager->persist($parcours6);

        $parcours7 = new ApcParcours();
        $parcours7->setId(7)
            ->setLibelle('Patrimoine et Finance')
            ->setCode('PF')
            ->setActif(false)
            ->setApcReferentiel($referentiel2);
        $manager->persist($parcours7);

        $parcours8 = new ApcParcours();
        $parcours8->setId(8)
            ->setLibelle('Entreprise et Association')
            ->setCode('EA')
            ->setActif(true)
            ->setApcReferentiel($referentiel2);
        $manager->persist($parcours8);

        $manager->flush();
    }
}