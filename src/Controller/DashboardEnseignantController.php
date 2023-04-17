<?php

namespace App\Controller;

use App\Repository\GroupeRepository;
use App\Repository\UsersRepository;
use App\Repository\EtudiantRepository;
use App\Repository\AnneeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Service\Attribute\Required;

class DashboardEnseignantController extends AbstractController
{

    public function __construct(
        protected GroupeRepository $groupeRepository,
        protected EtudiantRepository $etudiantRepository,
        #[Required] public Security $security
    )
    {
    }

    #[Route('/dashboard/enseignant', name: 'enseignant_dashboard')]
    public function index(
        UsersRepository $usersRepository
    ): Response
    {
        $usersRepository->findAll();
        $userId = $this->getUser()->getUserIdentifier();
        // Récupérer les groupes de l'utilisateur connecté
        $enseignant = $this->security->getUser()->getEnseignant();
//        $groupes = $this->groupeRepository->findBy(['enseignant' => $enseignant]);
        $groupes = $this->groupeRepository->findAll();
        $etudiants = [];
        foreach ($groupes as $groupe) {
            $etudiants = array_merge($etudiants, $groupe->getEtudiants()->toArray());
        }
        // Récupérer les années dans une variable


        foreach ($etudiants as $etudiant) {
            $etudiant->getPortfolios()->toArray();
        }

        if ($this->isGranted('ROLE_ENSEIGNANT')) {

            if ($userId === 'enseignant') {
                return $this->render('dashboard_enseignant/index.html.twig', [
                    'controller_name' => 'DashboardController', 'admin' => '/admin?_switch_user=_exit'
                ]);
            } else {

                return $this->render('dashboard_enseignant/index.html.twig', [
                    'controller_name' => 'DashboardController',
                    'admin' => '',
                    'groupes' => $groupes,
                    'etudiants' => $etudiants
                ]);
            }
        } else {

            return $this->redirectToRoute('app_dashboard');
        }
    }
}
