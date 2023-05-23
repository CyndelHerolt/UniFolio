<?php

namespace App\DataFixtures;

use App\Entity\Departement;
use App\Repository\DiplomeRepository;
use App\Repository\EnseignantRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DepartementFixture extends Fixture implements OrderedFixtureInterface
{

    private $enseignantRepository;
    private $diplomeRepository;

    public function __construct(
        EnseignantRepository $enseignantRepository,
        DiplomeRepository $diplomeRepository,
    )
    {
        $this->enseignantRepository = $enseignantRepository;
        $this->diplomeRepository = $diplomeRepository;
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
        $enseignant = $this->enseignantRepository->findOneBy(['username' => 'enseignant']);
        $diplome1 = $this->diplomeRepository->findOneBy(['sigle' => 'MMI-Crea']);
        $diplome2 = $this->diplomeRepository->findOneBy(['sigle' => 'MMI-DWeb-DI']);
        $diplome3 = $this->diplomeRepository->findOneBy(['sigle' => 'MMI-DWeb-DI-FC']);
        $diplome4 = $this->diplomeRepository->findOneBy(['sigle' => 'MMI-Strat-UX-FC']);
        $diplome5 = $this->diplomeRepository->findOneBy(['sigle' => 'MMI']);

        $departement1 = new Departement();
        $departement1->setLibelle('MMI')
            ->setId(1)
            ->setCouleur('#0096ff')
            ->setDescription('Le département  Métiers de l’Internet et du Multimédia  de l’IUT de Troyes (précédemment Services et Réseaux de Communication) forme les acteurs de l’Internet, des médias numériques, de la communication plurimédia, de la création.')
            ->addDiplome($diplome1)
            ->addDiplome($diplome2)
            ->addDiplome($diplome3)
            ->addDiplome($diplome4)
            ->addDiplome($diplome5)
            ;

        $manager->persist($departement1);

        $manager->flush();
    }
}
