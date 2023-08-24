<?php

namespace App\Controller;

use App\Components\Trace\TraceRegistry;
use App\Repository\DepartementRepository;
use App\Repository\EnseignantRepository;
use App\Repository\EtudiantRepository;
use App\Repository\GroupeRepository;
use App\Repository\SemestreRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Contracts\Service\Attribute\Required;

#[Route('/etudiant')]
class DashboardEtudiantController extends BaseController
{
    public function __construct(

        protected GroupeRepository $groupeRepository,
        protected EtudiantRepository $etudiantRepository,
        #[Required] public Security $security,
        RequestStack $requestStack,
        DepartementRepository $departementRepository,
        SemestreRepository $semestreRepository,
        TokenStorageInterface $tokenStorage,
        EnseignantRepository $enseignantRepository,
    )
    {
    }

    #[Route('/dashboard', name: 'etudiant_dashboard')]
    public function index(
        UsersRepository $usersRepository,
        TraceRegistry   $traceRegistry,
    ): Response
    {

        $data_user = $this->dataUserSession;

        $usersRepository->findAll();
        $userId = $this->getUser()->getUserIdentifier();

        if ($this->isGranted('ROLE_ETUDIANT')) {

            if ($userId === 'etudiant') {
                return $this->render('dashboard_etudiant/index.html.twig', [
                    'admin' => '/admin?_switch_user=_exit',
                    'traces' => $traceRegistry->getTypeTraces(),
                    'data_user' => $data_user,
                ]);
            } else {
                return $this->render('dashboard_etudiant/index.html.twig', [
                    'admin' => '',
                    'traces' => $traceRegistry->getTypeTraces(),
                    'data_user' => $data_user,
                ]);
            }
        }  else {
            return $this->render('security/accessDenied.html.twig');
        }
    }
}
