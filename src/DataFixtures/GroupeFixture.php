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
    private $enseignantRepository;
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
        return 10;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $etudiant1 = $this->etudiantRepository->findOneBy(['nom' => 'etudiant1']);
        $etudiant2 = $this->etudiantRepository->findOneBy(['nom' => 'etudiant2']);
        $etudiant3 = $this->etudiantRepository->findOneBy(['nom' => 'etudiant3']);
        $etudiant4 = $this->etudiantRepository->findOneBy(['nom' => 'etudiant4']);
        $etudiant5 = $this->etudiantRepository->findOneBy(['nom' => 'etudiant5']);
        $etudiant6 = $this->etudiantRepository->findOneBy(['nom' => 'etudiant6']);
        $etudiant7 = $this->etudiantRepository->findOneBy(['nom' => 'etudiant7']);
        $etudiant8 = $this->etudiantRepository->findOneBy(['nom' => 'etudiant8']);

        $enseignant1 = $this->enseignantRepository->findOneBy(['nom' => 'enseignant1']);
        $enseignant2 = $this->enseignantRepository->findOneBy(['nom' => 'enseignant2']);
        $enseignant3 = $this->enseignantRepository->findOneBy(['nom' => 'enseignant3']);
        $enseignant4 = $this->enseignantRepository->findOneBy(['nom' => 'enseignant4']);

        $typeGroupe1 = $this->typeGroupeRepository->findOneBy(['libelle' => 'CM']);
        $typeGroupe2 = $this->typeGroupeRepository->findOneBy(['libelle' => 'TD']);
        $typeGroupe3 = $this->typeGroupeRepository->findOneBy(['libelle' => 'TP']);

        $groupe1 = new Groupe();
        $groupe1->setLibelle('CM')
            ->setCodeApogee('TS123S1CM')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe1)
            ->setEnseignant($enseignant1)
            ->setEnseignant($enseignant2)
            ->setEnseignant($enseignant3)
            ->setEnseignant($enseignant4)
            ->addEtudiant($etudiant1)
            ->addEtudiant($etudiant2)
            ->addEtudiant($etudiant3)
            ->addEtudiant($etudiant4)
            ->addEtudiant($etudiant5)
            ->addEtudiant($etudiant6)
            ->addEtudiant($etudiant7)
            ->addEtudiant($etudiant8);

        $manager->persist($groupe1);

        $groupe2 = new Groupe();
        $groupe2->setLibelle('TD AB')
            ->setCodeApogee('TS123S1TDAB')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe2)
            ->setEnseignant($enseignant1)
            ->setEnseignant($enseignant2)
            ->addEtudiant($etudiant1)
            ->addEtudiant($etudiant2);

        $manager->persist($groupe2);

        $groupe3 = new Groupe();
        $groupe3->setLibelle('TD CD')
            ->setCodeApogee('TS123S1TDCD')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe2)
            ->setEnseignant($enseignant2)
            ->setEnseignant($enseignant3)
            ->addEtudiant($etudiant3)
            ->addEtudiant($etudiant4);

        $manager->persist($groupe3);

        $groupe4 = new Groupe();
        $groupe4->setLibelle('TD EF')
            ->setCodeApogee('TS123S1TDEF')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe2)
            ->setEnseignant($enseignant3)
            ->setEnseignant($enseignant4)
            ->addEtudiant($etudiant5)
            ->addEtudiant($etudiant6);

        $manager->persist($groupe4);

        $groupe5 = new Groupe();
        $groupe5->setLibelle('TD GH')
            ->setCodeApogee('TS123S1TDGH')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe2)
            ->setEnseignant($enseignant1)
            ->setEnseignant($enseignant4)
            ->addEtudiant($etudiant7)
            ->addEtudiant($etudiant8);

        $manager->persist($groupe5);

        $groupe6 = new Groupe();
        $groupe6->setLibelle('TP A')
            ->setCodeApogee('TS123S1TPA')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe3)
            ->setEnseignant($enseignant1)
            ->setEnseignant($enseignant2)
            ->addEtudiant($etudiant1);

        $manager->persist($groupe6);

        $groupe7 = new Groupe();
        $groupe7->setLibelle('TP B')
            ->setCodeApogee('TS123S1TPB')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe3)
            ->setEnseignant($enseignant2)
            ->setEnseignant($enseignant3)
            ->addEtudiant($etudiant2);

        $manager->persist($groupe7);

        $groupe8 = new Groupe();
        $groupe8->setLibelle('TP C')
            ->setCodeApogee('TS123S1TPC')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe3)
            ->setEnseignant($enseignant3)
            ->setEnseignant($enseignant4)
            ->addEtudiant($etudiant3);

        $manager->persist($groupe8);

        $groupe9 = new Groupe();
        $groupe9->setLibelle('TP D')
            ->setCodeApogee('TS123S1TPD')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe3)
            ->setEnseignant($enseignant4)
            ->setEnseignant($enseignant1)
            ->addEtudiant($etudiant4);

        $manager->persist($groupe9);

        $groupe10 = new Groupe();
        $groupe10->setLibelle('TP E')
            ->setCodeApogee('TS123S1TPE')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe3)
            ->setEnseignant($enseignant1)
            ->setEnseignant($enseignant2)
            ->addEtudiant($etudiant5);

        $manager->persist($groupe10);

        $groupe11 = new Groupe();
        $groupe11->setLibelle('TP F')
            ->setCodeApogee('TS123S1TPF')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe3)
            ->setEnseignant($enseignant2)
            ->setEnseignant($enseignant3)
            ->addEtudiant($etudiant6);

        $manager->persist($groupe11);

        $groupe12 = new Groupe();
        $groupe12->setLibelle('TP G')
            ->setCodeApogee('TS123S1TPG')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe3)
            ->setEnseignant($enseignant3)
            ->setEnseignant($enseignant4)
            ->addEtudiant($etudiant7);

        $manager->persist($groupe12);

        $groupe13 = new Groupe();
        $groupe13->setLibelle('TP H')
            ->setCodeApogee('TS123S1TPH')
            ->setOrdre(1)
            ->setTypeGroupe($typeGroupe3)
            ->setEnseignant($enseignant4)
            ->setEnseignant($enseignant1)
            ->addEtudiant($etudiant8);

        $manager->persist($groupe13);

        $manager->flush();
    }
}