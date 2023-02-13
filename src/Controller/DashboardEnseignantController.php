<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardEnseignantController extends AbstractController
{
    #[Route('/dashboard/enseignant', name: 'enseignant_dashboard')]
    public function enseignant(UsersRepository $usersRepository): Response
    {
        $usersRepository->findAll();
        $userId = $this->getUser()->getUserIdentifier();

        if ($this->isGranted('ROLE_ENSEIGNANT')) {

            if ($userId === 'enseignant') {
                return $this->render('dashboard_enseignant/index.html.twig', [
                    'controller_name' => 'DashboardController', 'admin' => '/admin?_switch_user=_exit'
                ]);
            } else {
                return $this->render('dashboard_enseignant/index.html.twig', [
                    'controller_name' => 'DashboardController', 'admin' => ''
                ]);
            }
        } else {

            return $this->redirectToRoute('app_dashboard');
        }
    }
}