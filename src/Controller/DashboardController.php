<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Controller;

use App\Repository\TraceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    private readonly RequestStack $session;

    public function __construct(RequestStack $session)
    {
        $this->session = $session;
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        $user = $this->getUser();

        if ($this->isGranted('ROLE_ETUDIANT')) {
            if ($this->session->getSession()->get('departement')) {
                return $this->redirectToRoute('etudiant_dashboard');
            } else {
                return $this->redirectToRoute('old_etudiant_dashboard');
            }
        } elseif ($this->isGranted('ROLE_ENSEIGNANT')) {
            return $this->redirectToRoute('enseignant_dashboard');
        } elseif ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin');
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}
