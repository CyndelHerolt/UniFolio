<?php

namespace App\DataFixtures;

use App\Entity\Trace;
use App\Repository\BibliothequeRepository;
use App\Repository\EtudiantRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TraceFixture extends Fixture
{

    public function __construct(
        protected EtudiantRepository     $etudiantRepository,
        protected BibliothequeRepository $bibliothequeRepository,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $trace1 = new Trace();

        $trace1->setTypeTrace('App\Components\Trace\TypeTrace\TraceTypeImage')
            ->setDateCreation(new \DateTimeImmutable())
            ->setDateModification(new \DateTimeImmutable())
            ->setTitre('trace n°1')
            ->setContenu('files_directory/image.png')
            ->setDescription('un commentaire pour justifier la pertinence de la trace.');

        $biblio = $this->bibliothequeRepository->findOneBy(['id' => 1]);
        $trace1->setBibliotheque($biblio);

        $manager->persist($trace1);

        //---------------------------------------------------------
        //---------------------------------------------------------
        //---------------------------------------------------------

        $trace2 = new Trace();

        $trace2->setTypeTrace('App\Components\Trace\TypeTrace\TraceTypeLien')
            ->setDateCreation(new \DateTimeImmutable())
            ->setDateModification(new \DateTimeImmutable())
            ->setTitre('trace n°2')
            ->setContenu('https://github.com/CyndelHerolt')
            ->setDescription('un commentaire pour justifier la pertinence de la trace.');

        $biblio = $this->bibliothequeRepository->findOneBy(['id' => 1]);
        $trace2->setBibliotheque($biblio);

        $manager->persist($trace2);

        //---------------------------------------------------------
        //---------------------------------------------------------
        //---------------------------------------------------------

        $trace3 = new Trace();

        $trace3->setTypeTrace('App\Components\Trace\TypeTrace\TraceTypePdf')
            ->setDateCreation(new \DateTimeImmutable())
            ->setDateModification(new \DateTimeImmutable())
            ->setTitre('trace n°3')
            ->setContenu('files_directory/pdf.pdf')
            ->setDescription('un commentaire pour justifier la pertinence de la trace.');

        $biblio = $this->bibliothequeRepository->findOneBy(['id' => 1]);
        $trace3->setBibliotheque($biblio);

        $manager->persist($trace3);

        //---------------------------------------------------------
        //---------------------------------------------------------
        //---------------------------------------------------------

        $trace4 = new Trace();

        $trace4->setTypeTrace('App\Components\Trace\TypeTrace\TraceTypeVideo')
            ->setDateCreation(new \DateTimeImmutable())
            ->setDateModification(new \DateTimeImmutable())
            ->setTitre('trace n°4')
            ->setContenu('https://www.youtube.com/watch?v=qJK_9eyRQl8')
            ->setDescription('un commentaire pour justifier la pertinence de la trace.');

        $biblio = $this->bibliothequeRepository->findOneBy(['id' => 1]);
        $trace4->setBibliotheque($biblio);

        $manager->persist($trace4);

        $manager->flush();
    }
}