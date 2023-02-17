<?php

namespace App\Controller;

use App\Entity\Enseignant;
use App\Entity\Etudiant;
use App\Entity\Users;
use App\Repository\EtudiantRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ProfilController extends AbstractController
{

    //todo: mode édition

    #[Route('/profil', name: 'app_profil')]
    public function profil(UsersRepository $usersRepository, EtudiantRepository $etudiantRepository): Response
    {
        $user = $usersRepository->findOneBy(['username' => $this->getUser()->getUserIdentifier()]);
        $userId = $this->getUser()->getUserIdentifier();

        if ($this->isGranted('ROLE_ETUDIANT')) {
            $etudiant = $user->getEtudiant();

            // Si un objet Etudiant est trouvé, vous pouvez accéder à ses propriétés
            if ($etudiant instanceof Etudiant) {
                $etudiant->setPrenom('Jean');
                $etudiant->setNom('Dupont');
                $etudiant->setMailPerso('JD@mail.com');
                $nom = $etudiant->getNom();
                $prenom = $etudiant->getPrenom();
                $email = $etudiant->getMailPerso();

            }
        }

        return $this->render('profil/index.html.twig', [
            'user' => $userId,
            'prenom' => $prenom,
            'nom' => $nom,
            'email' => $email,
        ]);
    }
}