<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Controller;

use App\Classes\DataUserSession;
use App\Entity\Enseignant;
use App\Repository\EnseignantDepartementRepository;
use App\Repository\EnseignantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/enseignant')]
class SecurityController extends AbstractController
{
    public function __construct(
        protected DataUserSession $dataUserSession,
    ) {
    }

    #[Route('/security', name: 'app_security')]
    public function index(): Response
    {
        return $this->render('security/choix-departement.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    #[Route(path: '/choix-departement', name: 'app_choix_departement')]
    public function choixDepartement(
        Request $request,
        EnseignantDepartementRepository $enseignantDepartementRepository,
        EnseignantRepository $enseignantRepository,
        RequestStack $session,
        EntityManagerInterface $entityManager,
    ) {
        if ($this->isGranted('ROLE_ENSEIGNANT')) {
            $user = $this->getUser();
            $enseignant = $enseignantRepository->findOneBy(['username' => $user->getUsername()]);
            if (!$enseignant) {
                return $this->redirectToRoute('app_login', ['message' => 'Vous devez être intervenant ou enseignant pour accéder à cette page.']);
            }

            $departements = $enseignantDepartementRepository->findByEnseignant($enseignant);
            $update = null;
            // si le formulaire est envoyé
            if ('POST' === $request->getMethod()) {
                $update = $enseignantDepartementRepository->findOneBy(['enseignant' => $enseignant, 'defaut' => true]);
                // Si aucun departement est Defaut = true on set Defaut à true pour le departement sélectionné
                if (null === $update) {
                    $update = $enseignantDepartementRepository->findOneBy(['enseignant' => $enseignant, 'departement' => $request->request->get('departement')]);
                    $update->setDefaut(true);
                    $this->redirectToRoute('app_dashboard');
                    $entityManager->flush();
                } else {
                    // Si un departement est Defaut = true on set Defaut à false pour ce département et on set Defaut à true pour le departement sélectionné
                    $update->setDefaut(false);
                    // On set Defaut à true pour le departement sélectionné
                    $update = $enseignantDepartementRepository->findOneBy(['enseignant' => $enseignant, 'departement' => $request->request->get('departement')]);
                    // dd($update);
                    $update->setDefaut(true);
                    $this->dataUserSession->setDepartement($update->getDepartement());

                    $this->redirectToRoute('app_dashboard');
                    $entityManager->flush();
                }

                if (null !== $update->getDepartement()) {
                    $this->addFlash('success', 'Formation par défaut sauvegardée');
//                    $session->getSession()->set('departement', $update->getDepartement()); // on sauvegarde
                    $this->dataUserSession->setDepartement($update->getDepartement());

                    return $this->redirectToRoute('app_accueil');
                }
            }
            return $this->render(
                'security/choix-departement.html.twig',
                ['departements' => $departements, 'user' => $user]
            );
        } else {
            return $this->render('security/accessDenied.html.twig');
        }
    }
}
