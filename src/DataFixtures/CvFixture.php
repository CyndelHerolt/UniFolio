<?php

namespace App\DataFixtures;

use App\Entity\Cv;
use App\Repository\EtudiantRepository;
use App\Repository\ExperienceRepository;
use App\Repository\FormationRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CvFixture extends Fixture implements OrderedFixtureInterface
{

    private $etudiantRepository;
    private $experienceRepository;
    private $formationRepository;

    public function __construct(
        EtudiantRepository $etudiantRepository,
        ExperienceRepository $experienceRepository,
        FormationRepository $formationRepository
    )
    {
        $this->etudiantRepository = $etudiantRepository;
        $this->experienceRepository = $experienceRepository;
        $this->formationRepository = $formationRepository;
    }

    public function load(ObjectManager $manager)
    {
        $exp1 = $this->experienceRepository->findOneBy(['poste' => 'Développeur web']);
        $exp2 = $this->experienceRepository->findOneBy(['poste' => 'Chargé de clientèle']);
        $exp3 = $this->experienceRepository->findOneBy(['poste' => 'Stagiaire Assistant commercial']);
        $exp4 = $this->experienceRepository->findOneBy(['poste' => 'Ouvrier BTP multiservices']);

        $form1 = $this->formationRepository->findOneBy(['diplome' => 'BUT MMI']);
        $form2 = $this->formationRepository->findOneBy(['diplome' => 'Passerelle vers un BUT']);
        $form3 = $this->formationRepository->findOneBy(['diplome' => 'Diplome d\'accès aux études universitaires']);
        $form4 = $this->formationRepository->findOneBy(['diplome' => 'BEP Vente Métiers de la Relation Client']);


        $cv1 = new Cv();
        $cv1->setIntitule('CV 1')
            ->setDateCreation(new \DateTimeImmutable('now'))
            ->setDescription('Ma description ...')
            ->setHardSkills(['PHP', 'Symfony', 'HTML', 'CSS', 'JS'])
            ->setSoftSkills(['Travail en équipe', 'Rigueur', 'Autonomie'])
            ->setLangues(['Français', 'Anglais', 'Espagnol'])
            ->setReseaux(['https://www.linkedin.com/in/cyndel-herolt/', 'https://github.com/CyndelHerolt'])
            ->addExperience($exp2)
            ->addExperience($exp1)
            ->addExperience($exp3)
            ->addFormation($form1)
            ->addFormation($form2)
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
            ->setReseaux(['https://www.linkedin.com/in/cyndel-herolt/', 'https://github.com/CyndelHerolt'])
            ->addExperience($exp1)
            ->addExperience($exp2)
            ->addExperience($exp3)
            ->addFormation($form1)
            ->addFormation($form2)
            ->addFormation($form3)
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
            ->setReseaux(['https://www.linkedin.com/in/cyndel-herolt/', 'https://github.com/CyndelHerolt'])
            ->addExperience($exp3)
            ->addExperience($exp2)
            ->addExperience($exp4)
            ->addExperience($exp1)
            ->addFormation($form1)
            ->addFormation($form4)
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
            ->setReseaux(['https://www.linkedin.com/in/cyndel-herolt/', 'https://github.com/CyndelHerolt'])
            ->addExperience($exp1)
            ->addExperience($exp2)
            ->addFormation($form1)
            ->addFormation($form2)
            ->addFormation($form3)
            ->addFormation($form4)
        ;

        $etudiant = $this->etudiantRepository->findOneBy(['mail_univ' => 'etudiant@mail.com']);
        $cv4->setEtudiant($etudiant)
            ->setDateCreation(new \DateTimeImmutable('now'))
            ->setDescription('Ma description ...')
            ->setHardSkills(['PHP', 'Symfony', 'HTML', 'CSS', 'JS'])
            ->setSoftSkills(['Travail en équipe', 'Rigueur', 'Autonomie'])
            ->setLangues(['Français', 'Anglais', 'Espagnol'])
            ->setReseaux(['https://www.linkedin.com/in/cyndel-herolt/', 'https://github.com/CyndelHerolt'])
            ->addExperience($exp2)
            ->addExperience($exp4)
            ->addExperience($exp3)
            ->addFormation($form1)
            ->addFormation($form3)
        ;

        $manager->persist($cv4);

        $manager->flush();
    }

    public function getOrder()
    {
        return 8;
    }
}
