<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardEtudiantController extends AbstractController
{
    #[Route('/dashboard/etudiant', name: 'etudiant_dashboard')]
    public function etudiant(): Response
    {
        return $this->render('dashboard_etudiant/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}