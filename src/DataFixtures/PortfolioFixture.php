<?php

namespace App\DataFixtures;

use App\Entity\Portfolio;
use App\Repository\EtudiantRepository;
use App\Repository\PageRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PortfolioFixture extends Fixture implements OrderedFixtureInterface
{
    private $pageRepository;
    private $etudiantRepository;

    public function __construct(PageRepository $pageRepository, EtudiantRepository $etudiantRepository)
    {
        $this->pageRepository = $pageRepository;
        $this->etudiantRepository = $etudiantRepository;
    }
    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $page1 = $this->pageRepository->findOneBy(['intitule' => 'Page 1']);
        $page2 = $this->pageRepository->findOneBy(['intitule' => 'Page 2']);
        $page3 = $this->pageRepository->findOneBy(['intitule' => 'Page 3']);
        $page4 = $this->pageRepository->findOneBy(['intitule' => 'Page 4']);

        $portfolio1 = new Portfolio();

        $portfolio1->setIntitule('Portfolio 1')
            ->setVisibilite(true)
            ->setBanniere('files_directory/640c667419045.jpg')
            ->addPage($page1)
            ->addPage($page2);

        $etudiant = $this->etudiantRepository->findOneBy(['mail_univ' => 'etudiant@mail.com']);
        $portfolio1->setEtudiant($etudiant);

        $manager->persist($portfolio1);

        $portfolio2 = new Portfolio();

        $portfolio2->setIntitule('Portfolio 2')
            ->setVisibilite(false)
            ->setBanniere('files_directory/640c666e893d2.png')
            ->addPage($page3)
            ->addPage($page4);

        $etudiant = $this->etudiantRepository->findOneBy(['mail_univ' => 'etudiant@mail.com']);
        $portfolio2->setEtudiant($etudiant);

        $manager->persist($portfolio2);

        $portfolio3 = new Portfolio();

        $portfolio3->setIntitule('Portfolio 3')
            ->setVisibilite(true)
            ->setBanniere('files_directory/640c667419045.jpg')
            ->addPage($page2)
            ->addPage($page4);

        $etudiant = $this->etudiantRepository->findOneBy(['mail_univ' => 'etudiant@mail.com']);
        $portfolio3->setEtudiant($etudiant);

        $manager->persist($portfolio3);

        $portfolio4 = new Portfolio();

        $portfolio4->setIntitule('Portfolio 4')
            ->setVisibilite(false)
            ->setBanniere('files_directory/640c666e893d2.png')
            ->addPage($page3)
            ->addPage($page1);

        $etudiant = $this->etudiantRepository->findOneBy(['mail_univ' => 'etudiant@mail.com']);
        $portfolio4->setEtudiant($etudiant);

        $manager->persist($portfolio4);

        $portfolio5 = new Portfolio();

        $portfolio5->setIntitule('Portfolio 5')
            ->setVisibilite(false)
            ->setBanniere('files_directory/650c666e893d2.png')
            ->addPage($page3)
            ->addPage($page1);

        $etudiant = $this->etudiantRepository->findOneBy(['nom' => 'etudiant1']);
        $portfolio5->setEtudiant($etudiant);

        $manager->persist($portfolio5);

        $portfolio6 = new Portfolio();

        $portfolio6->setIntitule('Portfolio 6')
            ->setVisibilite(true)
            ->setBanniere('files_directory/660c666e893d2.png')
            ->addPage($page4)
            ->addPage($page2);

        $etudiant = $this->etudiantRepository->findOneBy(['nom' => 'etudiant2']);
        $portfolio6->setEtudiant($etudiant);

        $manager->persist($portfolio6);

        $portfolio7 = new Portfolio();

        $portfolio7->setIntitule('Portfolio 7')
            ->setVisibilite(true)
            ->setBanniere('files_directory/660c666e893d2.png')
            ->addPage($page3)
            ->addPage($page1);

        $etudiant = $this->etudiantRepository->findOneBy(['nom' => 'etudiant5']);
        $portfolio7->setEtudiant($etudiant);

        $manager->persist($portfolio7);

        $portfolio8 = new Portfolio();

        $portfolio8->setIntitule('Portfolio 8')
            ->setVisibilite(true)
            ->setBanniere('files_directory/660c666e893d2.png')
            ->addPage($page2)
            ->addPage($page1);

        $etudiant = $this->etudiantRepository->findOneBy(['nom' => 'etudiant6']);
        $portfolio8->setEtudiant($etudiant);

        $manager->persist($portfolio8);

        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getOrder()
    {
        return 5;
    }
}
