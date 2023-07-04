<?php

namespace App\Controller;

use App\Components\Trace\TraceRegistry;
use App\Components\Trace\TypeTrace\TraceTypeImage;
use App\Components\Trace\TypeTrace\TraceTypeLien;
use App\Components\Trace\TypeTrace\TraceTypePdf;
use App\Components\Trace\TypeTrace\TraceTypeVideo;
use App\Entity\OrdrePage;
use App\Entity\OrdreTrace;
use App\Entity\Page;
use App\Entity\Trace;
use App\Entity\Validation;
use App\Form\PageType;
use App\Form\PortfolioType;
use App\Repository\ApcNiveauRepository;
use App\Repository\BibliothequeRepository;
use App\Repository\CompetenceRepository;
use App\Repository\OrdrePageRepository;
use App\Repository\OrdreTraceRepository;
use App\Repository\PageRepository;
use App\Repository\PortfolioRepository;
use App\Repository\TraceRepository;
use App\Repository\ValidationRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/portfolio/process', name: 'app_portfolio_process_')]
class PortfolioProcessController extends BaseController
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
        TraceRegistry          $traceRegistry,
        Security               $security,
        CompetenceRepository   $competenceRepository,
        ApcNiveauRepository    $apcNiveauRepository,
        ValidationRepository   $validationRepository,
        OrdrePageRepository    $ordrePageRepository,
        OrdreTraceRepository   $ordreTraceRepository,
    ): Response
    {
        // passer step dans l'url pr récup
        $step = $request->query->get('step', 'portfolio');
        $form = null;

        // Récupérer la variable 'id' de la requête
        $id = $request->attributes->get('id');
        $portfolio = $portfolioRepository->findOneBy(['id' => $id]);

        $pages = $portfolio->getPages();

        switch ($step) {

            case 'portfolio':
                $form = $this->createForm(PortfolioType::class, $portfolio);
                break;

            case 'savePortfolio':
                $form = $this->createForm(PortfolioType::class, $portfolio);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $portfolioRepository->save($portfolio, true);
                    return $this->redirectToRoute('app_portfolio_process_step', [
                        'id' => $portfolio->getId(),
                        'step' => 'portfolio']);
                }
                break;

            case 'addPage':
                $etudiant = $this->getUser()->getEtudiant();
                $biblio = $bibliothequeRepository->findOneBy(['etudiant' => $etudiant]);
                $traces = $biblio->getTraces();
                $page = $pageRepository->findOneBy(['id' => $request->query->get('page')]);
                $ordrePage = $ordrePageRepository->findOneBy(['page' => $page]);

                if ($ordrePage !== null) {
                    if ($ordrePage->getOrdre() == 1) {
                        $ordreMin = true;
                    } else {
                        $ordreMax = $portfolio->getOrdrePages()->count();
                    }
                }

                if ($page) {
                    $page = $pageRepository->findOneBy(['id' => $request->query->get('page')]);
                    $existingTraces = $page->getTrace();
                    $portfolio->addPage($page);
                    $pageRepository->save($page, true);
                } else {
                    $page = new Page();
                    $page->setIntitule('Nouvelle page');
                    $portfolio->addPage($page);
                    if ($portfolio->getOrdrePages()->count() > 0) {
                        $ordreMax = $portfolio->getOrdrePages()->last();
                        $ordre = $ordreMax->getOrdre() + 1;
                    } else {
                        $ordre = 1;
                    }
                    $newOrdrePage = new OrdrePage();
                    $newOrdrePage->setOrdre($ordre);
                    $newOrdrePage->setPage($page);
                    $newOrdrePage->setPortfolio($portfolio);
                    $portfolio->addOrdrePage($newOrdrePage);

                    $ordrePageRepository->save($newOrdrePage, true);
                    $pageRepository->save($page, true);

                    return $this->redirectToRoute('app_portfolio_process_step', [
                        'id' => $id,
                        'step' => 'addPage',
                        'page' => $page->getId(),
                    ]);
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

            case 'left':
                $page = $pageRepository->findOneBy(['id' => $request->query->get('page')]);

                $ordrePage = $ordrePageRepository->findOneBy(['page' => $page, 'portfolio' => $portfolio]);
                $ordre = $ordrePage->getOrdre();

                $previousPage = $ordrePageRepository->findOneBy(['ordre' => $ordre - 1, 'portfolio' => $portfolio]);

                if ($previousPage) {
                    $ordrePage->setOrdre($ordre - 1);
                    $previousPage->setOrdre($previousPage->getOrdre() + 1);
                } else {
                    $ordrePage->setOrdre($ordre == 1);
                }

                $ordrePageRepository->save($ordrePage, true);

                return $this->redirectToRoute('app_portfolio_process_step', [
                    'id' => $id,
                    'step' => 'addPage',
                    'page' => $page->getId(),
                ]);

            case 'right':
                $page = $pageRepository->findOneBy(['id' => $request->query->get('page')]);

                $ordrePage = $ordrePageRepository->findOneBy(['page' => $page, 'portfolio' => $portfolio]);
                $ordre = $ordrePage->getOrdre();

                $nextPage = $ordrePageRepository->findOneBy(['ordre' => $ordre + 1, 'portfolio' => $portfolio]);

                $ordrePage->setOrdre($ordre + 1);
                $nextPage->setOrdre($nextPage->getOrdre() - 1);
                $ordrePageRepository->save($ordrePage, true);

                return $this->redirectToRoute('app_portfolio_process_step', [
                    'id' => $id,
                    'step' => 'addPage',
                    'page' => $page->getId(),
                ]);

            case 'editPage':
                $etudiant = $this->getUser()->getEtudiant();
                $biblio = $bibliothequeRepository->findOneBy(['etudiant' => $etudiant]);
                $traces = $biblio->getTraces();

                // Récupérer les traces de la bibliothèque
                $page = $pageRepository->findOneBy(['id' => $request->query->get('page')]);
                $ordrePage = $ordrePageRepository->findOneBy(['page' => $page]);
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
                        'step' => 'addPage',
                        'page' => $page->getId(),
                    ]);
                }
                break;

            case 'deletePage':
                $page = $pageRepository->findOneBy(['id' => $request->query->get('page')]);
                $ordrePage = $ordrePageRepository->findOneBy(['page' => $page]);
                // pour chaque page dont l'ordre est supérieur à celle qu'on supprime, on décrémente l'ordre
                $ordre = $ordrePage->getOrdre();
                $ordrePages = $ordrePageRepository->findBy(['portfolio' => $portfolio]);
                foreach ($ordrePages as $ordreOthersPage) {
                    if ($ordreOthersPage->getOrdre() > $ordre) {
                        $ordreOthersPage->setOrdre($ordreOthersPage->getOrdre() - 1);
                        $ordrePageRepository->save($ordreOthersPage, true);
                    }
                }
                $portfolio->removeOrdrePage($ordrePage);
                $ordrePageRepository->remove($ordrePage, true);
                $portfolio->removePage($page);
                $pageRepository->remove($page, true);

                return $this->redirectToRoute('app_portfolio_process_step', [
                    'id' => $id,
                    'step' => 'portfolio',
                ]);

            case 'addTrace':
                $trace = $traceRepository->findOneBy(['id' => $request->query->get('trace')]);
                $page = $pageRepository->findOneBy(['id' => $request->query->get('page')]);

                if ($trace) {
                    $trace->addPage($page);
                    $traceRepository->save($trace, true);
                } else {
                    $trace = new Trace();
                    $trace->setTitre('Nouvelle trace');
                    $trace->addPage($page);

                    $ordre = 1;
                    // ajouter 1 à l'ordre de toutes les traces de la page
                    $ordreTraces = $ordreTraceRepository->findBy(['page' => $page]);
                    foreach ($ordreTraces as $ordreTrace) {
                        $ordreTrace->setOrdre($ordreTrace->getOrdre() + 1);
                    }

                    $newOrdreTrace = new OrdreTrace();
                    $newOrdreTrace->setOrdre($ordre);
                    $newOrdreTrace->setTrace($trace);
                    $newOrdreTrace->setPage($page);
                    $page->addOrdreTrace($newOrdreTrace);
                    $ordreTraceRepository->save($newOrdreTrace, true);

                    $traceRepository->save($trace, true);
                }
                return $this->redirectToRoute('app_portfolio_process_step', [
                    'id' => $id,
                    'step' => 'addPage',
                    'page' => $page->getId(),
                ]);

            case 'up':
                $trace = $traceRepository->findOneBy(['id' => $request->query->get('trace')]);

                $page = $pageRepository->findOneBy(['id' => $request->query->get('page')]);

                $ordreTrace = $ordreTraceRepository->findOneBy(['trace' => $trace, 'page' => $page]);
                $ordre = $ordreTrace->getOrdre();

                $previousTrace = $ordreTraceRepository->findOneBy(['ordre' => $ordre - 1, 'page' => $page]);

                if ($previousTrace) {
                    $ordreTrace->setOrdre($ordre - 1);
                    $previousTrace->setOrdre($previousTrace->getOrdre() + 1);
                } else {
                    $ordreTrace->setOrdre($ordre == 1);
                }

                $ordreTraceRepository->save($ordreTrace, true);

                return $this->redirectToRoute('app_portfolio_process_step', [
                    'id' => $id,
                    'step' => 'addPage',
                    'page' => $page->getId(),
                ]);

            case 'down':
                $trace = $traceRepository->findOneBy(['id' => $request->query->get('trace')]);

                $page = $pageRepository->findOneBy(['id' => $request->query->get('page')]);

                $ordreTrace = $ordreTraceRepository->findOneBy(['trace' => $trace, 'page' => $page]);
                $ordre = $ordreTrace->getOrdre();

                $nextTrace = $ordreTraceRepository->findOneBy(['ordre' => $ordre + 1, 'page' => $page]);

                $ordreTrace->setOrdre($ordre + 1);
                $nextTrace->setOrdre($nextTrace->getOrdre() - 1);

                $ordreTraceRepository->save($ordreTrace, true);


                return $this->redirectToRoute('app_portfolio_process_step', [
                    'id' => $id,
                    'step' => 'addPage',
                    'page' => $page->getId(),
                ]);

            case 'editTrace':
                $trace = $traceRepository->findOneBy(['id' => $request->query->get('trace')]);
                $type = $request->query->get('type');

                $page = $ordreTraceRepository->findOneBy(['trace' => $trace])->getPage();

                if ($trace->getTypeTrace() == null) {
                    $typesNewTrace = $traceRegistry->getTypeTraces();
                } else {
                    // passer directement à l'étape suivante
                    return $this->redirectToRoute('app_portfolio_process_step', [
                        'id' => $id,
                        'step' => 'formTrace',
                        'trace' => $trace->getId(),
                        'type' => $type,
                    ]);
                }
                break;

            case 'formTrace':
                $trace = $traceRepository->findOneBy(['id' => $request->query->get('trace')]);
                $type = $request->query->get('type');

                $page = $ordreTraceRepository->findOneBy(['trace' => $trace])->getPage();

                $traceType = $traceRegistry->getTypeTrace($type);

                $user = $security->getUser()->getEtudiant();

                $semestre = $user->getSemestre();
                $annee = $semestre->getAnnee();

                $dept = $this->dataUserSession->getDepartement();

                $referentiel = $dept->getApcReferentiels();

                $competences = $competenceRepository->findBy(['referentiel' => $referentiel->first()]);

                foreach ($competences as $competence) {
                    $niveaux[] = $apcNiveauRepository->findByAnnee($competence, $annee->getOrdre());
                }

                foreach ($niveaux as $niveau) {
                    foreach ($niveau as $niv) {
                        $competencesNiveau[] = $niv->getCompetences()->getLibelle();
                    }
                }

                $form = $this->createForm($traceType::FORM, $trace, ['user' => $user, 'competences' => $competencesNiveau]);

                $existingCompetences = [];
                foreach ($trace->getValidations() as $validation) {
                    $existingCompetences[] = $validation->getCompetences()->getLibelle();
                }

                // Pré remplissage du formulaire
                $form->get('competences')->setData($existingCompetences);

                $trace->setTypetrace($type);

                break;

            case 'deleteTrace':
                $trace = $traceRepository->findOneBy(['id' => $request->query->get('trace')]);
                $ordreTrace = $ordreTraceRepository->findOneBy(['trace' => $trace]);
                $page = $pageRepository->findOneBy(['id' => $request->query->get('page')]);

                //pour chaque trace dont l'ordre est supérieur à celui de la trace à supprimer, on décrémente l'ordre
                $ordre = $ordreTrace->getOrdre();
                $ordreTraces = $ordreTraceRepository->findBy(['page' => $page]);
                foreach ($ordreTraces as $ordreOthersTrace) {
                    if ($ordreOthersTrace->getOrdre() > $ordre) {
                        $ordreOthersTrace->setOrdre($ordreOthersTrace->getOrdre() - 1);
                        $ordreTraceRepository->save($ordreOthersTrace, true);
                    }
                }

                $page->removeOrdreTrace($ordreTrace);
                $ordreTraceRepository->remove($ordreTrace, true);
                $page->removeTrace($trace);

                return $this->redirectToRoute('app_portfolio_process_step', [
                    'id' => $id,
                    'step' => 'addPage',
                    'page' => $page->getId(),
                ]);

            case 'saveTrace':
                $trace = $traceRepository->findOneBy(['id' => $request->query->get('trace')]);
                $type = $request->query->get('type');

                $traceType = $traceRegistry->getTypeTrace($type);

                $page = $ordreTraceRepository->findOneBy(['trace' => $trace])->getPage()->getId();

                $user = $security->getUser()->getEtudiant();

                $semestre = $user->getSemestre();
                $annee = $semestre->getAnnee();

                $dept = $this->dataUserSession->getDepartement();

                $referentiel = $dept->getApcReferentiels();

                $competences = $competenceRepository->findBy(['referentiel' => $referentiel->first()]);

                foreach ($competences as $competence) {
                    $niveaux[] = $apcNiveauRepository->findByAnnee($competence, $annee->getOrdre());
                }

                foreach ($niveaux as $niveau) {
                    foreach ($niveau as $niv) {
                        $competencesNiveau[] = $niv->getCompetences()->getLibelle();
                    }
                }

                $form = $this->createForm($traceType::FORM, $trace, ['user' => $user, 'competences' => $competencesNiveau]);
                $form->handleRequest($request);
                if ($form->isSubmitted()
                    && $form->isValid()
                ) {
                    $existingCompetences = [];
                    foreach ($trace->getValidations() as $validation) {
                        $existingCompetences[] = $validation->getCompetences()->getLibelle();
                    }

                    $competencesForm = $form->get('competences')->getData();

                    $competences = $competenceRepository->findBy(['libelle' => $competencesForm]);

                    $validations = $trace->getValidations();

                    foreach ($competences as $competence) {

                        // Si la compétence n'est pas déjà liée à la trace
                        if (!in_array($competence->getLibelle(), $existingCompetences)) {
                            $validation = new Validation();
                            $validation->setEtat(0);
                            $competence->addValidation($validation);
                            $trace->addValidation($validation);
                        }

                        // supprimer les validations des compétences non sélectionnées
                        foreach ($validations as $validation) {
                            if (!in_array($validation->getCompetences()->getLibelle(), $competencesForm)) {
                                $validation->getCompetences()->removeValidation($validation);
                                $trace->removeValidation($validation);
                                $validationRepository->remove($validation);
                            }
                        }
                    }

                    if ($trace->getTypetrace() == TraceTypeImage::class
                    ) {
                        if (isset($request->request->All()['img'])) {
                            $existingContenu = $request->request->All()['img'];
                        } else {
                            $existingContenu = null;
                        }
                    } elseif ($trace->getTypetrace() == TraceTypePdf::class
                    ) {
                        if (isset($request->request->All()['pdf'])) {
                            $existingContenu = $request->request->All()['pdf'];
                        } else {
                            $existingContenu = null;
                        }
                    } elseif ($trace->getTypetrace() == TraceTypeLien::class
                    ) {
                        if (isset($request->request->All()['trace_type_lien']['contenu'])) {
                            $existingContenu = $request->request->All()['trace_type_lien']['contenu'];
                        } else {
                            $existingContenu = null;
                        }
                    } elseif ($trace->getTypetrace() == TraceTypeVideo::class
                    ) {
                        if (isset($request->request->All()['trace_type_video']['contenu'])) {
                            $existingContenu = $request->request->All()['trace_type_video']['contenu'];
                        } else {
                            $existingContenu = null;
                        }
                    } else {
                        $existingContenu = null;
                    }

                    if ($traceType->save($form, $trace, $traceRepository, $traceRegistry, $existingContenu)['success']) {

                        $form->getData()->setDatemodification(new \DateTimeImmutable());
                        $trace->setTypeTrace($type);
                        $traceRepository->save($trace, true);
                    }

                    return $this->redirectToRoute('app_portfolio_process_step', [
                        'id' => $id,
                        'step' => 'addPage',
                        'page' => $page
                    ]);
                }
                //todo: gérer les erreurs
                $error = [

                ];

                return $this->json(['success' => false, 'error' => $form->getErrors(true, false)], 500);
        }

        return $this->render('portfolio_process/step/_step.html.twig', ['step' => $step,
            'form' => $form ?? null,
            'liste' => $liste ?? null,
            'page' => $page ?? null,
            'pages' => $pages ?? null,
            'traces' => $traces ?? null,
            'existingTraces' => $existingTraces ?? null,
            'trace' => $trace ?? null,
            'id' => $id ?? null,
            'portfolio' => $portfolio ?? null,
            'typesTrace' => $typesTrace ?? null,
            'typesNewTrace' => $typesNewTrace ?? null,
            'traceType' => $traceType ?? null,
            'ordrePage' => $ordrePage ?? null,
            'ordreTrace' => $ordreTrace ?? null,
            'ordreMax' => $ordreMax ?? null,
            'ordreMin' => $ordreMin ?? null,
            'ordreMaxTrace' => $ordreMaxTrace ?? null,
            'ordreMinTrace' => $ordreMinTrace ?? null,]);
    }
}
