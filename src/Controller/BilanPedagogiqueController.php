<?php

namespace App\Controller;

use App\Entity\Portfolio;
use App\Repository\AnneeRepository;
use App\Repository\BibliothequeRepository;
use App\Repository\OrdrePageRepository;
use App\Repository\OrdreTraceRepository;
use App\Repository\PortfolioRepository;
use App\Repository\TraceRepository;
use App\Repository\ValidationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

#[Route('/etudiant')]
class BilanPedagogiqueController extends AbstractController
{

    public function __construct(
        #[Required] public Security   $security,
        public AnneeRepository        $anneeRepository,
        public BibliothequeRepository $bibliothequeRepository,
        public TraceRepository        $traceRepository,
        public ValidationRepository   $validationRepository,
        public PortfolioRepository    $portfolioRepository,
        public OrdrePageRepository    $ordrePageRepository,
        public OrdreTraceRepository   $ordreTraceRepository,
    )
    {
    }

    #[Route('/bilan-pedagogique', name: 'app_bilan_pedagogique')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ETUDIANT');

        //Récupérer les portfolios de l'utilisateur connecté
        $etudiant = $this->security->getUser()->getEtudiant();
        $portfolios = $this->portfolioRepository->findBy(['etudiant' => $etudiant]);

//        $ordrePages = $this->ordrePageRepository->findBy(['portfolio' => $portfolio], ['ordre' => 'ASC']);
//        $pages = [];
//        foreach ($ordrePages as $ordrePage) {
//            $pages[] = $ordrePage->getPage();
//        }
//
//        $traces = [];
//        foreach ($pages as $page) {
//            $ordreTraces = $this->ordreTraceRepository->findBy(['page' => $page], ['ordre' => 'ASC']);
//            foreach ($ordreTraces as $ordreTrace) {
//                $traces[] = $ordreTrace->getTrace();
//            }
//        }

        return $this->render('bilan_pedagogique/index.html.twig', [
            'portfolios' => $portfolios,
        ]);
    }
}
