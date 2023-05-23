<?php

namespace App\DataFixtures;

use App\Entity\Groupe;
use App\Repository\EtudiantRepository;
use App\Repository\TypeGroupeRepository;
use App\Repository\EnseignantRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;


class GroupeFixture extends Fixture implements OrderedFixtureInterface
{

    private $etudiantRepository;
    private $typeGroupeRepository;


    public function __construct(
        EtudiantRepository $etudiantRepository,
        EnseignantRepository $enseignantRepository,
        TypeGroupeRepository $typeGroupeRepository
    )
    {
        $this->etudiantRepository = $etudiantRepository;
        $this->enseignantRepository = $enseignantRepository;
        $this->typeGroupeRepository = $typeGroupeRepository;
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
        $etudiant1 = $this->etudiantRepository->findOneBy(['username' => 'etudiant']);

        $typeGroupe1 = $this->typeGroupeRepository->findOneBy(['libelle' => 'CM']);
        $typeGroupe2 = $this->typeGroupeRepository->findOneBy(['libelle' => 'TD']);
        $typeGroupe3 = $this->typeGroupeRepository->findOneBy(['libelle' => 'TP']);

        $groupe1 = new Groupe();
        $groupe1->setLibelle('CM')
            ->setId(1)
            ->setCodeApogee('TS123S1CM')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe1)
            ->addEtudiant($etudiant1);

        $manager->persist($groupe1);

        $groupe2 = new Groupe();
        $groupe2->setLibelle('TD AB')
            ->setId(2)
            ->setCodeApogee('TS123S1TDAB')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe2)
            ->addEtudiant($etudiant1);

        $manager->persist($groupe2);

        $groupe3 = new Groupe();
        $groupe3->setLibelle('TD CD')
            ->setId(3)
            ->setCodeApogee('TS123S1TDCD')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe2);

        $manager->persist($groupe3);

        $groupe4 = new Groupe();
        $groupe4->setLibelle('TD EF')
            ->setId(4)
            ->setCodeApogee('TS123S1TDEF')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe2);

        $manager->persist($groupe4);

        $groupe5 = new Groupe();
        $groupe5->setLibelle('TD GH')
            ->setId(5)
            ->setCodeApogee('TS123S1TDGH')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe2);

        $manager->persist($groupe5);

        $groupe6 = new Groupe();
        $groupe6->setLibelle('TP A')
            ->setId(6)
            ->setCodeApogee('TS123S1TPA')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe3)
            ->addEtudiant($etudiant1);

        $manager->persist($groupe6);

        $groupe7 = new Groupe();
        $groupe7->setLibelle('TP B')
            ->setId(7)
            ->setCodeApogee('TS123S1TPB')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe3);

        $manager->persist($groupe7);

        $groupe8 = new Groupe();
        $groupe8->setLibelle('TP C')
            ->setId(8)
            ->setCodeApogee('TS123S1TPC')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe3);

        $manager->persist($groupe8);

        $groupe9 = new Groupe();
        $groupe9->setLibelle('TP D')
            ->setId(9)
            ->setCodeApogee('TS123S1TPD')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe3);

        $manager->persist($groupe9);

        $groupe10 = new Groupe();
        $groupe10->setLibelle('TP E')
            ->setId(10)
            ->setCodeApogee('TS123S1TPE')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe3);

        $manager->persist($groupe10);

        $groupe11 = new Groupe();
        $groupe11->setLibelle('TP F')
            ->setId(11)
            ->setCodeApogee('TS123S1TPF')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe3);

        $manager->persist($groupe11);

        $groupe12 = new Groupe();
        $groupe12->setLibelle('TP G')
            ->setId(12)
            ->setCodeApogee('TS123S1TPG')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe3);

        $manager->persist($groupe12);

        $groupe13 = new Groupe();
        $groupe13->setLibelle('TP H')
            ->setId(13)
            ->setCodeApogee('TS123S1TPH')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe3);

        $manager->persist($groupe13);

        $manager->flush();
    }
}
