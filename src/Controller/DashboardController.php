<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(): Response
    {
        if ($this->isGranted('ROLE_ETUDIANT')) {
            return $this->redirectToRoute('etudiant_dashboard');
        }
        elseif ($this->isGranted('ROLE_ENSEIGNANT')) {
            return $this->redirectToRoute('enseignant_dashboard');
        }
        elseif ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin');
        }
        else {
            return $this->redirectToRoute('app_login');
        }

//        return $this->render('dashboard/index.html.twig', [
//            'controller_name' => 'DashboardController',
//        ]);
    }
}
