<?php

namespace App\DataFixtures;

use App\Entity\Trace;
use App\Repository\BibliothequeRepository;
use App\Repository\EtudiantRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class TraceFixture extends Fixture implements OrderedFixtureInterface
{

    private $bibliothequeRepository;
    private $etudiantRepository;

    public function __construct(BibliothequeRepository $bibliothequeRepository, EtudiantRepository $etudiantRepository)
    {
        $this->bibliothequeRepository = $bibliothequeRepository;
        $this->etudiantRepository = $etudiantRepository;
    }

    public function getOrder()
    {
        // Retourner un entier qui indique l'ordre de chargement des fixations
        return 3;
    }

    public function load(ObjectManager $manager): void
    {
        $trace1 = new Trace();

        $trace1->setTypeTrace('App\Components\Trace\TypeTrace\TraceTypeImage')
            ->setOrdre(1)
            ->setDateCreation(new \DateTimeImmutable())
            ->setDateModification(new \DateTimeImmutable())
            ->setTitre('trace n°1')
            ->setContenu(['files_directory/image.jpg'])
            ->setDescription('un commentaire pour justifier la pertinence de la trace.');

        $biblio = $this->bibliothequeRepository->findOneBy(['etudiant' => $this->etudiantRepository->findOneByMailUniv('etudiant@mail.com')]);
        $trace1->setBibliotheque($biblio);

        $manager->persist($trace1);

        //---------------------------------------------------------
        //---------------------------------------------------------
        //---------------------------------------------------------

        $trace2 = new Trace();

        $trace2->setTypeTrace('App\Components\Trace\TypeTrace\TraceTypeLien')
            ->setOrdre(2)
            ->setDateCreation(new \DateTimeImmutable())
            ->setDateModification(new \DateTimeImmutable())
            ->setTitre('trace n°2')
            ->setContenu(['https://github.com/CyndelHerolt'])
            ->setDescription('un commentaire pour justifier la pertinence de la trace.');

//        $biblio = $this->bibliothequeRepository->findOneBy(['id' => 1]);
//        $trace2->setBibliotheque($biblio);

        // Récupération de l'étudiant par son adresse e-mail
        $etudiant = $this->etudiantRepository->findOneBy(['mail_univ' => 'etudiant@mail.com']);

        // Récupération de la bibliothèque liée à cet étudiant
        $biblio = $this->bibliothequeRepository->findOneBy(['etudiant' => $etudiant]);

        // Lier l'objet Trace à cette bibliothèque
        $trace2->setBibliotheque($biblio);

        $manager->persist($trace2);

        //---------------------------------------------------------
        //---------------------------------------------------------
        //---------------------------------------------------------

        $trace3 = new Trace();

        $trace3->setTypeTrace('App\Components\Trace\TypeTrace\TraceTypePdf')
            ->setOrdre(3)
            ->setDateCreation(new \DateTimeImmutable())
            ->setDateModification(new \DateTimeImmutable())
            ->setTitre('trace n°3')
            ->setContenu(['files_directory/pdf.pdf'])
            ->setDescription('un commentaire pour justifier la pertinence de la trace.');

//        $biblio = $this->bibliothequeRepository->findOneBy(['id' => 1]);
//        $trace3->setBibliotheque($biblio);

        // Récupération de l'étudiant par son adresse e-mail
        $etudiant = $this->etudiantRepository->findOneBy(['mail_univ' => 'etudiant@mail.com']);

        // Récupération de la bibliothèque liée à cet étudiant
        $biblio = $this->bibliothequeRepository->findOneBy(['etudiant' => $etudiant]);

        // Lier l'objet Trace à cette bibliothèque
        $trace3->setBibliotheque($biblio);

        $manager->persist($trace3);

        //---------------------------------------------------------
        //---------------------------------------------------------
        //---------------------------------------------------------

        $trace4 = new Trace();

        $trace4->setTypeTrace('App\Components\Trace\TypeTrace\TraceTypeVideo')
            ->setOrdre(4)
            ->setDateCreation(new \DateTimeImmutable())
            ->setDateModification(new \DateTimeImmutable())
            ->setTitre('trace n°4')
            ->setContenu(['https://www.youtube.com/embed/iI3nE9wBn48'])
            ->setDescription('un commentaire pour justifier la pertinence de la trace.');

//        $biblio = $this->bibliothequeRepository->findOneBy(['id' => 1]);
//        $trace4->setBibliotheque($biblio);

        // Récupération de l'étudiant par son adresse e-mail
        $etudiant = $this->etudiantRepository->findOneBy(['mail_univ' => 'etudiant@mail.com']);

        // Récupération de la bibliothèque liée à cet étudiant
        $biblio = $this->bibliothequeRepository->findOneBy(['etudiant' => $etudiant]);

        // Lier l'objet Trace à cette bibliothèque
        $trace4->setBibliotheque($biblio);

        $manager->persist($trace4);

        $manager->flush();
    }
}
