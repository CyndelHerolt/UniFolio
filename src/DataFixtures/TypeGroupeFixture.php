<?php
/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\DataFixtures;

use App\Entity\TypeGroupe;
use App\Repository\SemestreRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TypeGroupeFixture extends Fixture implements OrderedFixtureInterface
{
    private $semestreRepository;

    public function __construct(SemestreRepository $semestreRepository)
    {
        $this->semestreRepository = $semestreRepository;
    }

    public function getOrder():int
    {
        return 9;
    }
    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $semestres = [];
        for ($i = 1; $i <= 32; $i++) {
            $semestre = $this->semestreRepository->find($i);
            if ($semestre !== null) {
                $semestres[] = $semestre;
            }
        }

        $typeGroupes = ['CM', 'TD', 'TP'];
        $idCounter = 1;
        foreach ($semestres as $semestre) {
            foreach ($typeGroupes as $typeGroupeLibelle) {
                $typeGroupe = new TypeGroupe();
                $typeGroupe->setId($idCounter);
                $typeGroupe->addSemestre($semestre);
                $typeGroupe->setLibelle($typeGroupeLibelle);
                $typeGroupe->setType($typeGroupeLibelle);

                $manager->persist($typeGroupe);
                $idCounter++;
            }
        }

        $manager->flush();
    }
}
