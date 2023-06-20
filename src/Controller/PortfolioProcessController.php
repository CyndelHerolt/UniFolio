<?php

// Equivalent à QuestionnaireController.php dans l'Intranet ?

namespace App\Controller;

use App\Form\PageType;
use App\Form\PortfolioType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PortfolioProcessController extends AbstractController
{
//    #[Route('/portfolio/process', name: 'portfolio_process')]
//    public function index(): Response
//    {
//        return $this->render('portfolio_process/index.html.twig');
//    }



    #[Route('/portfolio/process', name: 'step')]
    public function step(
        Request $request,
    ): Response
    {
        // passer step dans l'url pr récup
        $step = $request->query->get('step');
//        dd($step);
        $form = null;

//        $step = 'portfolio';

        switch ($step) {

            case 'portfolio':
                $form = $this->createForm(PortfolioType::class);
                break;

//            case 'newPage':

//            case 'addPage':

            case 'page':
                $form = $this->createForm(PageType::class);
                break;

        }

        return $this->renderForm('portfolio_process/index.html.twig', [
            'step' => $step,
            'form' => $form,
        ]);
    }
}
