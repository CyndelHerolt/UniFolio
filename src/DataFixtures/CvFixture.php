<?php

namespace App\DataFixtures;

use App\Entity\Cv;
use App\Repository\EtudiantRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CvFixture extends Fixture implements OrderedFixtureInterface {

    private $etudiantRepository;

    public function __construct(EtudiantRepository $etudiantRepository) {
        $this->etudiantRepository = $etudiantRepository;
    }

    public function load(ObjectManager $manager) {
        $cv1 = new Cv();
        $cv1->setIntitule('CV 1')
        ->setDateCreation(new \DateTimeImmutable('now'))
        ->setDescription('Ma description ...')
        ->setHardSkills(['PHP', 'Symfony', 'HTML', 'CSS', 'JS'])
        ->setSoftSkills(['Travail en équipe', 'Rigueur', 'Autonomie'])
        ->setLangues(['Français', 'Anglais', 'Espagnol'])
        ->setReseaux(['LinkedIn', 'GitHub', 'Twitter'])
        ;

        $etudiant = $this->etudiantRepository->findOneBy(['mail_univ' => 'etudiant@mail.com']);
        $cv1->setEtudiant($etudiant);

        $manager->persist($cv1);

        $cv2 = new Cv();
        $cv2->setIntitule('CV 2')
            ->setDateCreation(new \DateTimeImmutable('now'))
            ->setDescription('Ma description ...')
            ->setHardSkills(['PHP', 'Symfony', 'HTML', 'CSS', 'JS'])
            ->setSoftSkills(['Travail en équipe', 'Rigueur', 'Autonomie'])
            ->setLangues(['Français', 'Anglais', 'Espagnol'])
            ->setReseaux(['LinkedIn', 'GitHub', 'Twitter'])
        ;

        $etudiant = $this->etudiantRepository->findOneBy(['mail_univ' => 'etudiant@mail.com']);
        $cv2->setEtudiant($etudiant);

        $manager->persist($cv2);

        $cv3 = new Cv();
        $cv3->setIntitule('CV 3')
            ->setDateCreation(new \DateTimeImmutable('now'))
            ->setDescription('Ma description ...')
            ->setHardSkills(['PHP', 'Symfony', 'HTML', 'CSS', 'JS'])
            ->setSoftSkills(['Travail en équipe', 'Rigueur', 'Autonomie'])
            ->setLangues(['Français', 'Anglais', 'Espagnol'])
            ->setReseaux(['LinkedIn', 'GitHub', 'Twitter'])
        ;

        $etudiant = $this->etudiantRepository->findOneBy(['mail_univ' => 'etudiant@mail.com']);
        $cv3->setEtudiant($etudiant);

        $manager->persist($cv3);

        $cv4 = new Cv();
        $cv4->setIntitule('CV 4')
            ->setDateCreation(new \DateTimeImmutable('now'))
            ->setDescription('Ma description ...')
            ->setHardSkills(['PHP', 'Symfony', 'HTML', 'CSS', 'JS'])
            ->setSoftSkills(['Travail en équipe', 'Rigueur', 'Autonomie'])
            ->setLangues(['Français', 'Anglais', 'Espagnol'])
            ->setReseaux(['LinkedIn', 'GitHub', 'Twitter'])
        ;

        $etudiant = $this->etudiantRepository->findOneBy(['mail_univ' => 'etudiant@mail.com']);
        $cv4->setEtudiant($etudiant)
            ->setDateCreation(new \DateTimeImmutable('now'))
            ->setDescription('Ma description ...')
            ->setHardSkills(['PHP', 'Symfony', 'HTML', 'CSS', 'JS'])
            ->setSoftSkills(['Travail en équipe', 'Rigueur', 'Autonomie'])
            ->setLangues(['Français', 'Anglais', 'Espagnol'])
            ->setReseaux(['LinkedIn', 'GitHub', 'Twitter'])
        ;

        $manager->persist($cv4);

        $manager->flush();
    }

    public function getOrder() {
        return 6;
    }
}
