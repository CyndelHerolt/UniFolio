<?php

// Equivalent à QuestionnaireController.php dans l'Intranet ?

namespace App\Controller;

use App\Entity\Page;
use App\Entity\Trace;
use App\Form\PageType;
use App\Form\PortfolioType;
use App\Repository\BibliothequeRepository;
use App\Repository\PageRepository;
use App\Repository\PortfolioRepository;
use App\Repository\TraceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/portfolio/process', name: 'app_portfolio_process_')]
class PortfolioProcessController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(
        Request $request,
    ): Response
    {

        $id = $request->query->get('id');
//        dd($id);

        return $this->render('portfolio_process/index.html.twig', [
            'step' => 'portfolio',
            'id' => $id,
        ]);
    }


    #[Route('/step/{id}', name: 'step')]
    public function step(
        Request                $request,
        BibliothequeRepository $bibliothequeRepository,
        PageRepository         $pageRepository,
        TraceRepository        $traceRepository,
        PortfolioRepository    $portfolioRepository,
    ): Response
    {
        // passer step dans l'url pr récup
        $step = $request->query->get('step', 'portfolio');
//        dd($step);
        $form = null;

        // Récupérer la variable 'id' de la requête
        $id = $request->attributes->get('id');
        $portfolio = $portfolioRepository->findOneBy(['id' => $id]);

        $pages = $portfolio->getPages();

        switch ($step) {

            case 'portfolio':
                $form = $this->createForm(PortfolioType::class, $portfolio);
                break;

//            case 'newPage':

            case 'addPage':

                $etudiant = $this->getUser()->getEtudiant();
                $biblio = $bibliothequeRepository->findOneBy(['etudiant' => $etudiant]);
                $traces = $biblio->getTraces();

                if ($pageRepository->findOneBy(['id' => $request->query->get('page')])) {
                    $page = $pageRepository->findOneBy(['id' => $request->query->get('page')]);
                    $existingTraces = $page->getTrace();
                    $portfolio->addPage($page);
                    $pageRepository->save($page, true);
                } else {
                    $page = new Page();
                    $page->setIntitule('Nouvelle page');
                    $portfolio->addPage($page);
                    $pageRepository->save($page, true);
                }
                break;

            case 'page':

                $etudiant = $this->getUser()->getEtudiant();
                $biblio = $bibliothequeRepository->findOneBy(['etudiant' => $etudiant]);

                // Récupérer les traces de la bibliothèque
                $traces = $biblio->getTraces();

                // Récupérer les pages associées aux traces(donc les pages de l'étudiant connecté)
                $liste = [];
                foreach ($traces as $trace) {
                    $liste = array_merge($liste, $trace->getPages()->toArray());
//            Si deux pages sont les mêmes, ne les afficher qu'une seule fois
                    $liste = array_unique($liste, SORT_REGULAR);
                }

                $form = $this->createForm(PageType::class);
                break;

            case 'editPage':
                $etudiant = $this->getUser()->getEtudiant();
                $biblio = $bibliothequeRepository->findOneBy(['etudiant' => $etudiant]);
                $traces = $biblio->getTraces();

                // Récupérer les traces de la bibliothèque
                $page = $pageRepository->findOneBy(['id' => $request->query->get('page')]);
                $existingTraces = $page->getTrace();
                $form = $this->createForm(PageType::class, $page);
                break;

            case 'savePage':

                $page = $pageRepository->findOneBy(['id' => $request->query->get('page')]);

                $form = $this->createForm(PageType::class, $page);

                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $page = $form->getData();
                    $pageRepository->save($page, true);

                    return $this->redirectToRoute('app_portfolio_process_step', [
                        'id' => $id,
                        'step' => 'portfolio',
                    ]);
                }
                break;

            case 'deletePage':
                $page = $pageRepository->findOneBy(['id' => $request->query->get('page')]);
                $portfolio->removePage($page);
                $pageRepository->remove($page, true);

                return $this->redirectToRoute('app_portfolio_process_step', [
                    'id' => $id,
                    'step' => 'portfolio',
                ]);

            case 'addTrace':
                $page = $pageRepository->findOneBy(['id' => $request->query->get('page')]);

                if ($traceRepository->findOneBy(['id' => $request->query->get('trace')])) {
                    $trace = $traceRepository->findOneBy(['id' => $request->query->get('trace')]);
                    $trace->addPage($page);
                    $traceRepository->save($trace, true);
                } else {
                    $trace = new Trace();
                    $trace->setTitre('Nouvelle trace');
                    $trace->addPage($page);
                    $traceRepository->save($trace, true);
                }
                break;
        }

        return $this->render('portfolio_process/step/_step.html.twig', [
            'step' => $step,
            'form' => $form,
            'liste' => $liste ?? null,
            'page' => $page ?? null,
            'pages' => $pages ?? null,
            'traces' => $traces ?? null,
            'existingTraces' => $existingTraces ?? null,
            'trace' => $trace ?? null,
            'id' => $id ?? null,
            'portfolio' => $portfolio ?? null,
        ]);
    }
}
