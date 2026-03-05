<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
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
    private $typeGroupeRepository;

    public function __construct(
        TypeGroupeRepository $typeGroupeRepository
    ) {
        $this->typeGroupeRepository = $typeGroupeRepository;
    }

    public function getOrder(): int
    {
        return 10;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        // Récupérer les types de groupes...
        $typeGroupes = $this->typeGroupeRepository->findAll();

        // Définir les groupes pour chaque type de groupe
        $groupesForType = [
            'CM' => ['CM'],
            'TD' => ['TD AB', 'TD CD', 'TD EF', 'TD GH'],
            'TP' => ['TP A', 'TP B', 'TP C', 'TP D', 'TP E', 'TP F', 'TP G', 'TP H']
        ];
        $idCounter = 1;
        foreach ($typeGroupes as $typeGroupe) {
            $libelle = $typeGroupe->getLibelle();
            if (array_key_exists($libelle, $groupesForType)) {
                foreach ($groupesForType[$libelle] as $groupeLibelle) {
                    $semestres = $typeGroupe->getSemestre();
                    foreach ($semestres as $semestre) {
                        $annee = $semestre->getAnnee();
                        $diplome = $annee->getDiplome();
                        $apcParcours = $diplome->getApcParcours();
                    }
                    $groupe = new Groupe();
                    $groupe->setId($idCounter);
                    $groupe->setTypeGroupe($typeGroupe);
                    $groupe->setLibelle($groupeLibelle);
                    $groupe->setApcParcours($apcParcours);

                    $manager->persist($groupe);
                    $idCounter++;
                }
            }
        }

        // Flush all changes to the database
        $manager->flush();
    }
}
