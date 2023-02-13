<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardEtudiantController extends AbstractController
{
    #[Route('/dashboard/etudiant', name: 'etudiant_dashboard')]
    public function etudiant(UsersRepository $usersRepository): Response
    {
        $usersRepository->findAll();
        $userId = $this->getUser()->getUserIdentifier();

        if ($this->isGranted('ROLE_ETUDIANT')) {

            if ($userId === 'etudiant') {
                return $this->render('dashboard_etudiant/index.html.twig', [
                    'controller_name' => 'DashboardController', 'admin' => '/admin?_switch_user=_exit'
                ]);
            } else {
                return $this->render('dashboard_etudiant/index.html.twig', [
                    'controller_name' => 'DashboardController', 'admin' => ''
                ]);
            }
        } else {

            return $this->redirectToRoute('app_dashboard');
        }
    }
}