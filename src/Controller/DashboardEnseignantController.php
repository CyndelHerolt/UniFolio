<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardEnseignantController extends AbstractController
{
    #[Route('/dashboard/enseignant', name: 'enseignant_dashboard')]
    public function enseignant(): Response
    {
        return $this->render('dashboard_enseignant/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
