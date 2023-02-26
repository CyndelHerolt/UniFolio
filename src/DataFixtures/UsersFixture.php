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
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class UsersFixture extends Fixture implements OrderedFixtureInterface
{

    public function getOrder()
    {
        // Retourner un entier qui indique l'ordre de chargement des fixations
        return 1;
    }

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
        $etudiant = new Etudiant();
        $biblio = new Bibliotheque();

        $etudiant->setUsers($user2);

        $etudiant->setNom('Martin');
        $etudiant->setPrenom('Jean');
        $etudiant->setMailPerso('persoTest@mail.com');
        $etudiant->setMailUniv('etudiantTest@mail.com');
        $etudiant->setTelephone('0123456781');
        $etudiant->setBio('Vestibulum elementum odio lectus, vitae tempor ante viverra non. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Ut scelerisque bibendum ipsum, non tincidunt risus ultrices vel.');


        $biblio->setEtudiant($etudiant);

        $manager->persist($etudiant);
        $manager->persist($biblio);

        $manager->persist($user2);

        //---------------------------------------------------------
        //---------------------------------------------------------
        //---------------------------------------------------------

        $user3 = new Users();

        $password = $this->encoder->hashPassword($user3, 'test');

        $user3->setUsername('etudiantTest')
            ->setPassword($password)
            ->setRoles(['ROLE_ETUDIANT']);
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

        $manager->persist($etudiant);
        $manager->persist($biblio);

        $manager->persist($user3, $etudiant, $biblio);

        //---------------------------------------------------------
        //---------------------------------------------------------
        //---------------------------------------------------------

        $user4 = new Users();

        $password = $this->encoder->hashPassword($user4, 'test');

        $user4->setUsername('enseignant')
            ->setPassword($password)
            ->setRoles(['ROLE_ENSEIGNANT']);
        $enseignant = new Enseignant();

        $enseignant->setUsers($user4);

        $enseignant->setNom('Dupont');
        $enseignant->setPrenom('Marie');
        $enseignant->setMailPerso('persoTest@mail.com');
        $enseignant->setMailUniv('enseignantTest@mail.com');
        $enseignant->setTelephone('0123456782');

        $manager->persist($enseignant);
        $manager->persist($user4);

//---------------------------------------------------------
        //---------------------------------------------------------
        //---------------------------------------------------------

        $user5 = new Users();

        $password = $this->encoder->hashPassword($user5, 'test');

        $user5->setUsername('enseignantTest')
            ->setPassword($password)
            ->setRoles(['ROLE_ENSEIGNANT']);
        $enseignant = new Enseignant();
        $enseignant->setUsers($user5);

        $enseignant->setNom('Doe');
        $enseignant->setPrenom('Jane');
        $enseignant->setMailPerso('perso@mail.com');
        $enseignant->setMailUniv('enseignant@mail.com');
        $enseignant->setTelephone('0123456783');

        $manager->persist($enseignant);
        $manager->persist($user5);

        $manager->flush();
    }
}