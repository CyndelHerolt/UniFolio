<?php

namespace App\DataFixtures;

use App\Entity\Bibliotheque;
use App\Entity\Enseignant;
use App\Entity\Etudiant;
use App\Repository\BibliothequeRepository;
use App\Repository\EnseignantRepository;
use App\Repository\EtudiantRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UsersFixture extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $encoder,
        protected EtudiantRepository                 $etudiantRepository,
        protected BibliothequeRepository             $bibliothequeRepository,
        protected EnseignantRepository               $enseignantRepository,
    )
    {
    }

    public function load(ObjectManager $manager
    ): void
    {
        $user1 = new Users();

        $password = $this->encoder->hashPassword($user1, 'test');

        $user1->setUsername('admin')
            ->setPassword($password)
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($user1);

        //---------------------------------------------------------
        //---------------------------------------------------------
        //---------------------------------------------------------

        $user2 = new Users();

        $password = $this->encoder->hashPassword($user2, 'test');

        $user2->setUsername('etudiant')
            ->setPassword($password)
            ->setRoles(['ROLE_ETUDIANT']);
        if (in_array('ROLE_ETUDIANT', $user2->getRoles())) {
            $etudiant = new Etudiant();
            $biblio = new Bibliotheque();

            $etudiant->setUsers($user2);
            $biblio->setEtudiant($etudiant);
//            $this->etudiantRepository->$manager->persist($etudiant);
//            $this->bibliothequeRepository->$manager->persist($biblio);
        }

        $manager->persist($user2, $etudiant, $biblio);

        //---------------------------------------------------------
        //---------------------------------------------------------
        //---------------------------------------------------------

        $user3 = new Users();

        $password = $this->encoder->hashPassword($user3, 'test');

        $user3->setUsername('etudiantTest')
            ->setPassword($password)
            ->setRoles(['ROLE_ETUDIANT']);
        if (in_array('ROLE_ETUDIANT', $user3->getRoles())) {
            $etudiant = new Etudiant();
            $biblio = new Bibliotheque();

            $etudiant->setUsers($user3);

            $etudiant->setNom('Doe');
            $etudiant->setPrenom('John');
            $etudiant->setMailPerso('perso@mail.com');
            $etudiant->setMailUniv('etudiant@mail.com');
            $etudiant->setTelephone('0123456789');
            $etudiant->setBio('Vestibulum elementum odio lectus, vitae tempor ante viverra non. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Ut scelerisque bibendum ipsum, non tincidunt risus ultrices vel.');

            $biblio->setEtudiant($etudiant);
//            $this->etudiantRepository->$manager->persist($etudiant, true);
//            $this->bibliothequeRepository->$manager->persist($biblio, true);
        }

        $manager->persist($user3, $etudiant, $biblio);

        //---------------------------------------------------------
        //---------------------------------------------------------
        //---------------------------------------------------------

        $user4 = new Users();

        $password = $this->encoder->hashPassword($user4, 'test');

        $user4->setUsername('enseignant')
            ->setPassword($password)
            ->setRoles(['ROLE_ENSEIGNANT']);
        if (in_array('ROLE_ENSEIGNANT', $user4->getRoles())) {
            $enseignant = new Enseignant();
            $enseignant->setUsers($user4);
//            $this->enseignantRepository->$manager->persist($enseignant, true);
        }

        $manager->persist($user4, $enseignant);

//---------------------------------------------------------
        //---------------------------------------------------------
        //---------------------------------------------------------

        $user5 = new Users();

        $password = $this->encoder->hashPassword($user5, 'test');

        $user5->setUsername('enseignantTest')
            ->setPassword($password)
            ->setRoles(['ROLE_ENSEIGNANT']);
        if (in_array('ROLE_ENSEIGNANT', $user5->getRoles())) {
            $enseignant = new Enseignant();
            $enseignant->setUsers($user5);

            $enseignant->setNom('Doe');
            $enseignant->setPrenom('Jane');
            $enseignant->setMailPerso('perso@mail.com');
            $enseignant->setMailUniv('enseignant@mail.com');
            $enseignant->setTelephone('0123456789');

//            $this->enseignantRepository->$manager->persist($enseignant, true);
        }

        $manager->persist($user5, $enseignant);


        $manager->flush();
    }
}