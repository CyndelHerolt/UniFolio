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

    #[Route('/profil', name: 'app_profil')]
    public function profil(UsersRepository $usersRepository): Response
    {
        $user = $usersRepository->findOneBy(['username' => $this->getUser()->getUserIdentifier()]);
        $userId = $this->getUser()->getUserIdentifier();

        if ($this->isGranted('ROLE_ETUDIANT')) {
            $etudiant = $user->getEtudiant();

            // Si un objet Etudiant est trouvé, vous pouvez accéder à ses propriétés
            if ($etudiant instanceof Etudiant) {
                $nom = $etudiant->getNom();
                $prenom = $etudiant->getPrenom();
                $email_perso = $etudiant->getMailPerso();
                $email_univ = $etudiant->getMailUniv();
                $tel = $etudiant->getTelephone();
                $bio = $etudiant->getBio();
            }
        }
        elseif ($this->isGranted('ROLE_ENSEIGNANT')) {
            $enseignant = $user->getEnseignant();

            // Si un objet Enseignant est trouvé, vous pouvez accéder à ses propriétés
            if ($enseignant instanceof Enseignant) {
                $nom = $enseignant->getNom();
                $prenom = $enseignant->getPrenom();
                $email_perso = $enseignant->getMailPerso();
                $email_univ = $enseignant->getMailUniv();
                $tel = $enseignant->getTelephone();
                $groupes = $enseignant->getGroupes();
                $bio = null;
            }
        }

        return $this->render('profil/index.html.twig', [
            'user' => $userId,
            'prenom' => $prenom,
            'nom' => $nom,
            'email_perso' => $email_perso,
            'email_univ' => $email_univ,
            'tel' => $tel,
            'bio' => $bio,
            'groupes' => $groupes ?? null
        ]);
    }
}
