<?php
/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Controller;

use App\Entity\Commentaire;
use App\Event\CommentaireEvent;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use App\Repository\DepartementRepository;
use App\Repository\DiplomeRepository;
use App\Repository\EnseignantRepository;
use App\Repository\GroupeRepository;
use App\Repository\TraceRepository;
use App\Repository\UsersRepository;
use App\Repository\EtudiantRepository;
use App\Repository\AnneeRepository;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Service\Attribute\Required;

#[Route('/enseignant')]
class DashboardEnseignantController extends BaseController
{

    #[Route('/dashboard', name: 'enseignant_dashboard')]
    public function index(
        UsersRepository          $usersRepository,
        EnseignantRepository     $enseignantRepository,
        DepartementRepository    $departementRepository,
        TraceRepository          $traceRepository,
        CommentaireRepository    $commentaireRepository,
        EventDispatcherInterface $eventDispatcher
    ): Response
    {
        if ($this->isGranted('ROLE_ENSEIGNANT')) {

            $data_user = $this->dataUserSession;

            $usersRepository->findAll();
            $userId = $this->getUser()->getUserIdentifier();
            $enseignant = $enseignantRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
            $departement = $departementRepository->findDepartementEnseignantDefaut($enseignant);

            if ($userId === 'enseignant') {
//                foreach ($departement as $dept) {
//                    $data_user->setDepartement($dept);
//                }
                return $this->render('dashboard_enseignant/index.html.twig', [
                    'admin' => '/admin?_switch_user=_exit',
                    'data_user' => $data_user,
                ]);
            } else {
                return $this->render('dashboard_enseignant/index.html.twig', [
                    'admin' => '',
                    'data_user' => $data_user,
                ]);
            }
        } else {
            return $this->render('security/accessDenied.html.twig');
        }
    }
}
