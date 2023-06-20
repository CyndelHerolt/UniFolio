<?php

// Equivalent à QuestionnaireController.php dans l'Intranet ?

namespace App\Controller;

use App\Entity\Page;
use App\Entity\Trace;
use App\Form\PageType;
use App\Form\PortfolioType;
use App\Repository\BibliothequeRepository;
use App\Repository\PageRepository;
use App\Repository\TraceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/portfolio/process', name: 'app_portfolio_process_')]
class PortfolioProcessController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('portfolio_process/index.html.twig', [
            'step' => 'portfolio',
        ]);
    }


    #[Route('/step', name: 'step')]
    public function step(
        Request                $request,
        BibliothequeRepository $bibliothequeRepository,
        PageRepository         $pageRepository,
        TraceRepository        $traceRepository,
    ): Response
    {
        // passer step dans l'url pr récup
        $step = $request->query->get('step', 'portfolio');
//        dd($step);
        $form = null;

//        $step = 'portfolio';

        switch ($step) {

            case 'portfolio':
                $form = $this->createForm(PortfolioType::class);
                break;

//            case 'newPage':

            case 'addPage':

                $etudiant = $this->getUser()->getEtudiant();
                $biblio = $bibliothequeRepository->findOneBy(['etudiant' => $etudiant]);
                $traces = $biblio->getTraces();

                if ($pageRepository->findOneBy(['id' => $request->query->get('page')])) {
                    $page = $pageRepository->findOneBy(['id' => $request->query->get('page')]);
                } else {
                    $page = new Page();
                    $page->setIntitule('Nouvelle page');
                }

            case 'page':

                $etudiant = $this->getUser()->getEtudiant();
                $biblio = $bibliothequeRepository->findOneBy(['etudiant' => $etudiant]);

                // Récupérer les traces de la bibliothèque
                $traces = $biblio->getTraces();

                // Récupérer les pages associées aux traces(donc les pages de l'étudiant connecté)
                $pages = [];
                foreach ($traces as $trace) {
                    $pages = array_merge($pages, $trace->getPages()->toArray());
//            Si deux pages sont les mêmes, ne les afficher qu'une seule fois
                    $pages = array_unique($pages, SORT_REGULAR);
                }

                $form = $this->createForm(PageType::class);
                break;

            case 'editPage':
                //todo: si nouvelle page, créer une page vide ?
                $page = $pageRepository->findOneBy(['id' => $request->query->get('page')]);
                $form = $this->createForm(PageType::class, $page);
                break;

            case 'addTrace':
                if ($traceRepository->findOneBy(['id' => $request->query->get('trace')])) {
                    $trace = $traceRepository->findOneBy(['id' => $request->query->get('trace')]);
                } else {
                    $trace = new Trace();
                    $trace->setTitre('Nouvelle trace');
                }
        }

        return $this->render('portfolio_process/step/_step.html.twig', [
            'step' => $step,
            'form' => $form,
            'pages' => $pages ?? null,
            'page' => $page ?? null,
            'traces' => $traces ?? null,
            'trace' => $trace ?? null,
        ]);
    }
}
