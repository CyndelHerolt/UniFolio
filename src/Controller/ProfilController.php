<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */

namespace App\Controller;

use App\Entity\Enseignant;
use App\Entity\Etudiant;
use App\Entity\Users;
use App\Form\EnseignantType;
use App\Form\EtudiantPartialType;
use App\Repository\EnseignantRepository;
use App\Repository\EtudiantRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

#[Route('/profil')]
class ProfilController extends BaseController
{
    public function __construct(
        #[Required] public Security $security,
    )
    {
    }

    #[Route('/', name: 'app_profil')]
    public function profil(UsersRepository $usersRepository): Response
    {
        $data_user = $this->dataUserSession;
//        dd($data_user);
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
                $optAlt = $etudiant->isOptAlternance();
                $optStage = $etudiant->isOptStage();
            }
        } elseif ($this->isGranted('ROLE_ENSEIGNANT')) {
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
        } else {
            return $this->render('security/accessDenied.html.twig');
        }

        return $this->render('profil/index.html.twig', [
            'user' => $userId,
            'prenom' => $prenom,
            'nom' => $nom,
            'email_perso' => $email_perso,
            'email_univ' => $email_univ,
            'tel' => $tel,
            'bio' => $bio,
            'optAlternance' => $optAlt ?? null,
            'optStage' => $optStage ?? null,
            'groupes' => $groupes ?? null,
            'data_user' => $data_user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_profil_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request              $request,
        ?Etudiant            $etudiant,
        ?Enseignant          $enseignant,
        EtudiantRepository   $etudiantRepository,
        EnseignantRepository $enseignantRepository,
        ?int                 $id,
    ): Response
    {
        $user = $this->security->getUser();

        if ($this->isGranted('ROLE_ETUDIANT')) {
            if ($id == $user->getEtudiant()->getId()) {
                $form = $this->createForm(EtudiantPartialType::class, $etudiant);
            } else {
                return $this->render('security/accessDenied.html.twig');
            }
        } elseif ($this->isGranted('ROLE_ENSEIGNANT')) {
            if ($id == $user->getEnseignant()->getId()) {
                $form = $this->createForm(EnseignantType::class, $enseignant);
            } else {
                return $this->render('security/accessDenied.html.twig');
            }
        } else {
            return $this->render('security/accessDenied.html.twig');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isGranted('ROLE_ETUDIANT')) {
                if ($form->get('optAlternance')->getData() == true && $form->get('optStage')->getData() == true) {
                    // Si l'étudiant est à la recherche d'une alternance et d'un stage, on ne peut pas valider le formulaire
                    $this->addFlash('danger', 'Vous ne pouvez pas être à la recherche d\'une alternance et d\'un stage en même temps');
                    // redirection vers la page d'édition du profil
                    return $this->redirectToRoute('app_profil_edit', ['id' => $etudiant->getId()], Response::HTTP_SEE_OTHER);
                }
                $etudiantRepository->save($etudiant, true);
            } elseif ($this->isGranted('ROLE_ENSEIGNANT')) {
                $enseignantRepository->save($enseignant, true);
            }

            return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profil/edit.html.twig', [
            'enseignant' => $enseignant,
            'etudiant' => $etudiant,
            'form' => $form->createView(),
        ]);
    }
}
