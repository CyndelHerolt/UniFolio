<?php

namespace App\Controller;

use App\Components\Trace\TraceRegistry;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardEtudiantController extends AbstractController
{
    #[Route('/dashboard/etudiant', name: 'etudiant_dashboard')]
    public function index(
        UsersRepository $usersRepository,
        TraceRegistry   $traceRegistry,
    ): Response
    {
        $usersRepository->findAll();
        $userId = $this->getUser()->getUserIdentifier();

        if ($this->isGranted('ROLE_ETUDIANT')) {

            if ($userId === 'etudiant') {
                return $this->render('dashboard_etudiant/index.html.twig', [
                    'controller_name' => 'DashboardController',
                    'admin' => '/admin?_switch_user=_exit',
                    'traces' => $traceRegistry->getTypeTraces(),
                ]);
            } else {
                return $this->render('dashboard_etudiant/index.html.twig', [
                    'controller_name' => 'DashboardController',
                    'admin' => '',
                    'traces' => $traceRegistry->getTypeTraces(),
                ]);
            }
        } else {

            return $this->redirectToRoute('app_dashboard');
        }
    }
}