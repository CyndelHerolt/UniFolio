<?php

namespace App\Controller;

use App\Repository\DepartementRepository;
use App\Repository\DiplomeRepository;
use App\Repository\EnseignantRepository;
use App\Repository\GroupeRepository;
use App\Repository\SemestreRepository;
use App\Repository\TypeGroupeRepository;
use App\Repository\UsersRepository;
use App\Repository\EtudiantRepository;
use App\Repository\AnneeRepository;
use ContainerPEHGF1J\getDataUserSessionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Contracts\Service\Attribute\Required;

class DashboardEnseignantController extends BaseController
{

    public function __construct(

        protected GroupeRepository $groupeRepository,
        protected DiplomeRepository $diplomeRepository,
        protected EtudiantRepository $etudiantRepository,
        protected AnneeRepository $anneeRepository,
        #[Required] public Security $security,
        EnseignantRepository $enseignantRepository,
    )
    {
    }

    #[Route('/dashboard/enseignant', name: 'enseignant_dashboard')]
    public function index(
        UsersRepository $usersRepository
    ): Response
    {

        $data_user = $this->dataUserSession;
//        dd($data_user);

        $usersRepository->findAll();
        $userId = $this->getUser()->getUserIdentifier();

        if ($this->isGranted('ROLE_ENSEIGNANT')) {

            if ($userId === 'enseignant') {
                return $this->render('dashboard_enseignant/index.html.twig', [
                    'controller_name' => 'DashboardController', 'admin' => '/admin?_switch_user=_exit', 'departement' => $departement,
                ]);
            } else {

                return $this->render('dashboard_enseignant/index.html.twig', [
                    'controller_name' => 'DashboardController',
                    'admin' => '',
                    'data_user' => $data_user,
                ]);
            }
        } else {

            return $this->redirectToRoute('app_dashboard');
        }
    }
}
