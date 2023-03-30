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

        $etudiant->setNom('etudiant1');
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

        $user6 = new Users();

        $password = $this->encoder->hashPassword($user6, 'test');

        $user6->setUsername('etudiant2')
            ->setPassword($password)
            ->setRoles(['ROLE_ETUDIANT']);
        $etudiant = new Etudiant();
        $biblio = new Bibliotheque();

        $etudiant->setUsers($user6);

        $etudiant->setNom('etudiant2');
        $etudiant->setPrenom('Jean');
        $etudiant->setMailPerso('persoTest@mail.com');
        $etudiant->setMailUniv('etudiantTest@mail.com');
        $etudiant->setTelephone('0163456781');
        $etudiant->setBio('Vestibulum elementum odio lectus, vitae tempor ante viverra non. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Ut scelerisque bibendum ipsum, non tincidunt risus ultrices vel.');

        $biblio->setEtudiant($etudiant);

        $manager->persist($etudiant);
        $manager->persist($biblio);

        $manager->persist($user6);

        //---------------------------------------------------------
        //---------------------------------------------------------
        //---------------------------------------------------------

        $user7 = new Users();

        $password = $this->encoder->hashPassword($user7, 'test');

        $user7->setUsername('etudiant3')
            ->setPassword($password)
            ->setRoles(['ROLE_ETUDIANT']);
        $etudiant = new Etudiant();
        $biblio = new Bibliotheque();

        $etudiant->setUsers($user7);

        $etudiant->setNom('etudiant3');
        $etudiant->setPrenom('Jean');
        $etudiant->setMailPerso('persoTest@mail.com');
        $etudiant->setMailUniv('etudiantTest@mail.com');
        $etudiant->setTelephone('0173457781');
        $etudiant->setBio('Vestibulum elementum odio lectus, vitae tempor ante viverra non. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Ut scelerisque bibendum ipsum, non tincidunt risus ultrices vel.');

        $biblio->setEtudiant($etudiant);

        $manager->persist($etudiant);
        $manager->persist($biblio);

        $manager->persist($user7);

        //---------------------------------------------------------
        //---------------------------------------------------------
        //---------------------------------------------------------


        $user8 = new Users();

        $password = $this->encoder->hashPassword($user8, 'test');

        $user8->setUsername('etudiant4')
            ->setPassword($password)
            ->setRoles(['ROLE_ETUDIANT']);
        $etudiant = new Etudiant();
        $biblio = new Bibliotheque();

        $etudiant->setUsers($user8);

        $etudiant->setNom('etudiant4');
        $etudiant->setPrenom('Jean');
        $etudiant->setMailPerso('persoTest@mail.com');
        $etudiant->setMailUniv('etudiantTest@mail.com');
        $etudiant->setTelephone('0183458881');
        $etudiant->setBio('Vestibulum elementum odio lectus, vitae tempor ante viverra non. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Ut scelerisque bibendum ipsum, non tincidunt risus ultrices vel.');

        $biblio->setEtudiant($etudiant);

        $manager->persist($etudiant);
        $manager->persist($biblio);

        $manager->persist($user8);

        //---------------------------------------------------------
        //---------------------------------------------------------
        //---------------------------------------------------------

        $user9 = new Users();

        $password = $this->encoder->hashPassword($user9, 'test');

        $user9->setUsername('etudiant5')
            ->setPassword($password)
            ->setRoles(['ROLE_ETUDIANT']);
        $etudiant = new Etudiant();
        $biblio = new Bibliotheque();

        $etudiant->setUsers($user9);

        $etudiant->setNom('etudiant5');
        $etudiant->setPrenom('Jean');
        $etudiant->setMailPerso('persoTest@mail.com');
        $etudiant->setMailUniv('etudiantTest@mail.com');
        $etudiant->setTelephone('0193459991');
        $etudiant->setBio('Vestibulum elementum odio lectus, vitae tempor ante viverra non. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Ut scelerisque bibendum ipsum, non tincidunt risus ultrices vel.');

        $biblio->setEtudiant($etudiant);

        $manager->persist($etudiant);
        $manager->persist($biblio);

        $manager->persist($user9);

        //---------------------------------------------------------
        //---------------------------------------------------------
        //---------------------------------------------------------

        $user10 = new Users();

        $password = $this->encoder->hashPassword($user10, 'test');

        $user10->setUsername('etudiant6')
            ->setPassword($password)
            ->setRoles(['ROLE_ETUDIANT']);
        $etudiant = new Etudiant();
        $biblio = new Bibliotheque();

        $etudiant->setUsers($user10);

        $etudiant->setNom('etudiant6');
        $etudiant->setPrenom('Jean');
        $etudiant->setMailPerso('persoTest@mail.com');
        $etudiant->setMailUniv('etudiantTest@mail.com');
        $etudiant->setTelephone('0193454539');
        $etudiant->setBio('Vestibulum elementum odio lectus, vitae tempor ante viverra non. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Ut scelerisque bibendum ipsum, non tincidunt risus ultrices vel.');

        $biblio->setEtudiant($etudiant);

        $manager->persist($etudiant);
        $manager->persist($biblio);

        $manager->persist($user10);

        //---------------------------------------------------------
        //---------------------------------------------------------
        //---------------------------------------------------------

        $user11 = new Users();

        $password = $this->encoder->hashPassword($user11, 'test');

        $user11->setUsername('etudiant7')
            ->setPassword($password)
            ->setRoles(['ROLE_ETUDIANT']);
        $etudiant = new Etudiant();
        $biblio = new Bibliotheque();

        $etudiant->setUsers($user11);

        $etudiant->setNom('etudiant7');
        $etudiant->setPrenom('Jean');
        $etudiant->setMailPerso('persoTest@mail.com');
        $etudiant->setMailUniv('etudiantTest@mail.com');
        $etudiant->setTelephone('0193454539');
        $etudiant->setBio('Vestibulum elementum odio lectus, vitae tempor ante viverra non. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Ut scelerisque bibendum ipsum, non tincidunt risus ultrices vel.');

        $biblio->setEtudiant($etudiant);

        $manager->persist($etudiant);
        $manager->persist($biblio);

        $manager->persist($user11);

        //---------------------------------------------------------
        //---------------------------------------------------------
        //---------------------------------------------------------


        $user12 = new Users();

        $password = $this->encoder->hashPassword($user12, 'test');

        $user12->setUsername('etudiant8')
            ->setPassword($password)
            ->setRoles(['ROLE_ETUDIANT']);
        $etudiant = new Etudiant();
        $biblio = new Bibliotheque();

        $etudiant->setUsers($user12);

        $etudiant->setNom('etudiant8');
        $etudiant->setPrenom('Jean');
        $etudiant->setMailPerso('persoTest@mail.com');
        $etudiant->setMailUniv('etudiantTest@mail.com');
        $etudiant->setTelephone('0193454539');
        $etudiant->setBio('Vestibulum elementum odio lectus, vitae tempor ante viverra non. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Ut scelerisque bibendum ipsum, non tincidunt risus ultrices vel.');

        $biblio->setEtudiant($etudiant);

        $manager->persist($etudiant);
        $manager->persist($biblio);

        $manager->persist($user12);

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

        $enseignant->setNom('enseignant1');
        $enseignant->setPrenom('Marie');
        $enseignant->setMailPerso('persoTest@mail.com');
        $enseignant->setMailUniv('enseignantTest@mail.com');
        $enseignant->setTelephone('0123456782');

        $manager->persist($enseignant);
        $manager->persist($user4);

        //---------------------------------------------------------
        //---------------------------------------------------------
        //---------------------------------------------------------

        $user13 = new Users();

        $password = $this->encoder->hashPassword($user13, 'test');

        $user13->setUsername('enseignant2')
            ->setPassword($password)
            ->setRoles(['ROLE_ENSEIGNANT']);
        $enseignant = new Enseignant();

        $enseignant->setUsers($user13);

        $enseignant->setNom('enseignant2');
        $enseignant->setPrenom('Marie');
        $enseignant->setMailPerso('persoTest@mail.com');
        $enseignant->setMailUniv('enseignantTest@mail.com');
        $enseignant->setTelephone('0123456782');

        $manager->persist($enseignant);
        $manager->persist($user13);

        //---------------------------------------------------------
        //---------------------------------------------------------
        //---------------------------------------------------------

        $user14 = new Users();

        $password = $this->encoder->hashPassword($user14, 'test');

        $user14->setUsername('enseignant3')
            ->setPassword($password)
            ->setRoles(['ROLE_ENSEIGNANT']);
        $enseignant = new Enseignant();

        $enseignant->setUsers($user14);

        $enseignant->setNom('enseignant3');
        $enseignant->setPrenom('Marie');
        $enseignant->setMailPerso('persoTest@mail.com');
        $enseignant->setMailUniv('enseignantTest@mail.com');
        $enseignant->setTelephone('0123456782');

        $manager->persist($enseignant);
        $manager->persist($user14);

        //---------------------------------------------------------
        //---------------------------------------------------------
        //---------------------------------------------------------

        $user15 = new Users();

        $password = $this->encoder->hashPassword($user15, 'test');

        $user15->setUsername('enseignant4')
            ->setPassword($password)
            ->setRoles(['ROLE_ENSEIGNANT']);
        $enseignant = new Enseignant();

        $enseignant->setUsers($user15);

        $enseignant->setNom('enseignant4');
        $enseignant->setPrenom('Marie');
        $enseignant->setMailPerso('persoTest@mail.com');
        $enseignant->setMailUniv('enseignantTest@mail.com');
        $enseignant->setTelephone('0123456782');

        $manager->persist($enseignant);
        $manager->persist($user15);

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