<?php

namespace App\Controller;

use App\Repository\EtudiantRepository;
use App\Repository\PortfolioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

class VuePortfolioController extends AbstractController
{
    public function __construct(
        #[Required] public Security $security
    )
    {
    }

    #[Route('/etudiant/{id}/portfolio', name: 'app_vue_portfolio')]
    public function index(
        PortfolioRepository $portfolioRepository,
        EtudiantRepository $etudiantRepository,
        int $id,
    ): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ENSEIGNANT');

        $etudiant = $etudiantRepository->findOneBy(['id' => $id]);

        $portfolios = $portfolioRepository->findBy([
            'etudiant' => $etudiant
        ]);

        if (count($portfolios) > 1) {
            return $this->render('vue_portfolio/bibliotheque.html.twig', [
            'controller_name' => 'VuePortfolioController',
            'etudiant' => $etudiant,
            'portfolios' => $portfolios,
            ]);
        } elseif (count($portfolios) == 1) {
            return $this->render('vue_portfolio/bibliotheque.html.twig', [
                'controller_name' => 'VuePortfolioController',
                'etudiant' => $etudiant,
                'portfolios' => $portfolios,
            ]);
        } elseif (count($portfolios) == 0) {
            $this->addFlash('error', 'Aucun portfolio n\'a été trouvé pour cet étudiant');
        }
        return $this->render('dashboard_enseignant/index.html.twig', [

        ]);
    }


}
