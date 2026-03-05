<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\DataFixtures;

use App\Entity\Bibliotheque;
use App\Entity\Enseignant;
use App\Entity\EnseignantDepartement;
use App\Entity\Etudiant;
use App\Repository\BibliothequeRepository;
use App\Repository\DepartementRepository;
use App\Repository\EnseignantRepository;
use App\Repository\EtudiantRepository;
use App\Repository\GroupeRepository;
use App\Repository\SemestreRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class UsersFixture extends Fixture implements OrderedFixtureInterface
{
    public function getOrder(): int
    {
        // Retourner un entier qui indique l'ordre de chargement des fixations
        return 12;
    }

    public function __construct(
        private readonly UserPasswordHasherInterface $encoder,
        protected EtudiantRepository $etudiantRepository,
        protected BibliothequeRepository $bibliothequeRepository,
        protected EnseignantRepository $enseignantRepository,
        protected DepartementRepository $departementRepository,
        protected GroupeRepository $groupeRepository,
        protected SemestreRepository $semestreRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $user1 = new Users();

        $password = $this->encoder->hashPassword($user1, 'test');

        $user1->setUsername('admin')
            ->setPassword($password)
            ->setRoles(['ROLE_ADMIN'])
            ->setIsVerified(true)
        ;

        $manager->persist($user1);

        //---------------------------------------------------------
        //---------------------------------------------------------
        //---------------------------------------------------------

        // récupérer les groupes dont le l'id du parcours est 5
        $groupes = $this->groupeRepository->findBy(['apcParcours' => 5]);
        $semestre = $this->semestreRepository->findOneBy(['id' => 7]);

        $user2 = new Users();

        $password = $this->encoder->hashPassword($user2, 'test');

        $user2->setUsername('etudiant')
            ->setPassword($password)
            ->setRoles(['ROLE_ETUDIANT', 'ROLE_ADMIN', 'ROLE_TEST'])
            ->setEmail('etudiant@etudiant.univ-reims.fr')
            ->setIsVerified(true);
        $etudiant = new Etudiant();
        $biblio = new Bibliotheque();

        $etudiant->setUsers($user2);

        $etudiant->setNom('Herolt');
        $etudiant->setPrenom('Cyndel');
        $etudiant->setMailPerso('persoEtudiant@mail.com');
        $etudiant->setMailUniv('etudiant@etudiant.univ-reims.fr');
        $etudiant->setTelephone('0103456781');
        $etudiant->setBio('Vestibulum elementum odio lectus, vitae tempor ante viverra non. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Ut scelerisque bibendum ipsum, non tincidunt risus ultrices vel.');
        $etudiant->setUsername('etudiant');
        $etudiant->setSemestre($semestre);

        foreach ($groupes as $groupe) {
            $etudiant->addGroupe($groupe);
        }

        $biblio->setEtudiant($etudiant);

        $manager->persist($etudiant);
        $manager->persist($biblio);

        $manager->persist($user2);


        $user11 = new Users();

        $password = $this->encoder->hashPassword($user11, 'test');

        $user11->setUsername('enseignant')
            ->setPassword($password)
            ->setRoles(['ROLE_ENSEIGNANT', 'ROLE_ADMIN', 'ROLE_TEST'])
            ->setEmail('prof@univ-reims.fr')
            ->setIsVerified(true);
        $enseignant = new Enseignant();

        $enseignant->setUsers($user11);

        $enseignant->setNom('Jane');
        $enseignant->setPrenom('Doe');
        $enseignant->setMailPerso('persoTest@mail.com');
        $enseignant->setMailUniv('prof@univ-reims.fr');
        $enseignant->setTelephone('0123456782');
        $enseignant->setUsername('enseignant');
        $departement = $this->departementRepository->findOneBy(['id' => 1]);
        $newEnseignantDepartement = new EnseignantDepartement($enseignant, $departement);
        $enseignant->AddEnseignantDepartement($newEnseignantDepartement);

        $manager->persist($enseignant);
        $manager->persist($user11);
        $manager->flush();
    }
}
