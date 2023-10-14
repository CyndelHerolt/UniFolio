<?php
/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Controller;

use App\Repository\TraceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        $user = $this->getUser();

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
    }
}
