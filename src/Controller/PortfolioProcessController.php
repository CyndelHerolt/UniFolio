<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Controller;

use App\Components\Trace\TraceRegistry;
use App\Components\Trace\TypeTrace\TraceTypeImage;
use App\Components\Trace\TypeTrace\TraceTypeLien;
use App\Components\Trace\TypeTrace\TraceTypePdf;
use App\Components\Trace\TypeTrace\TraceTypeVideo;
use App\Entity\Cv;
use App\Entity\OrdrePage;
use App\Entity\OrdreTrace;
use App\Entity\Page;
use App\Entity\Trace;
use App\Entity\Validation;
use App\Form\CvType;
use App\Form\PageType;
use App\Form\PortfolioType;
use App\Repository\ApcNiveauRepository;
use App\Repository\BibliothequeRepository;
use App\Repository\CompetenceRepository;
use App\Repository\CvRepository;
use App\Repository\ExperienceRepository;
use App\Repository\FormationRepository;
use App\Repository\OrdrePageRepository;
use App\Repository\OrdreTraceRepository;
use App\Repository\PageRepository;
use App\Repository\PortfolioRepository;
use App\Repository\TraceRepository;
use App\Repository\ValidationRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('etudiant/portfolio/process', name: 'app_portfolio_process_')]
class PortfolioProcessController extends BaseController
{
    #[Route('/', name: 'index')]
    public function index(
        Request $request,
    ): Response {
        if ($this->isGranted('ROLE_ETUDIANT')) {
            $data_user = $this->dataUserSession;
            $id = $request->query->get('id');

            return $this->render('portfolio_process/index.html.twig', [
                'step' => 'portfolio',
                'id' => $id,
                'data_user' => $data_user,
            ]);
        } else {
            return $this->render('security/accessDenied.html.twig');
        }
    }

    #[Route('/step/{id}', name: 'step')]
    public function step(
        Request $request,
        BibliothequeRepository $bibliothequeRepository,
        PageRepository $pageRepository,
        TraceRepository $traceRepository,
        PortfolioRepository $portfolioRepository,
        TraceRegistry $traceRegistry,
        Security $security,
        CompetenceRepository $competenceRepository,
        ApcNiveauRepository $apcNiveauRepository,
        ValidationRepository $validationRepository,
        OrdrePageRepository $ordrePageRepository,
        OrdreTraceRepository $ordreTraceRepository,
        CvRepository $cvRepository,
        ExperienceRepository $experienceRepository,
        FormationRepository $formationRepository,
    ): Response {
            $data_user = $this->dataUserSession;
            $step = $request->query->get('step', 'portfolio');
            $form = null;

            $id = $request->attributes->get('id');
            $portfolio = $portfolioRepository->findOneBy(['id' => $id]);
            $user = $security->getUser()->getEtudiant();

        if ($this->isGranted('ROLE_ETUDIANT') && $portfolio->getEtudiant() == $user) {

            $ordrePages = $ordrePageRepository->findBy(['portfolio' => $portfolio], ['ordre' => 'ASC']);
            $pages = [];
            foreach ($ordrePages as $ordrePage) {
                $pages[] = $ordrePage->getPage();
            }

            $etudiant = $this->getUser()->getEtudiant();
            $cvs = $cvRepository->findBy(['etudiant' => $etudiant]);
            $cv = $portfolio->getCv();
            if ($cv != null) {
                $key = array_search($cv, $cvs);
                unset($cvs[$key]);
            }

            switch ($step) {
                case 'portfolio':
                    $form = $this->createForm(PortfolioType::class, $portfolio);
                    break;

                case 'deleteBanniere':
                    $banniere = $portfolio->getBanniere();
                    if ($banniere != $_ENV['SRC_FILES'] . '/banniere.jpg') {
                        unlink($banniere);
                    }
                    // bannière par défaut
                    $portfolio->setBanniere($_ENV['SRC_FILES'] . '/banniere.jpg');
                    $portfolioRepository->save($portfolio, true);
                    return $this->redirectToRoute('app_portfolio_process_step', [
                        'id' => $portfolio->getId(),
                        'step' => 'portfolio',
                        'data_user' => $data_user,
                    ]);

                case 'savePortfolio':
                    $form = $this->createForm(PortfolioType::class, $portfolio);
                    $form->handleRequest($request);
                    if ($form->isSubmitted() && $form->isValid()) {
                        $imageFile = $form['banniere']->getData();
                        if ($imageFile) {
                            $imageFileName = uniqid() . '.' . $imageFile->guessExtension();
                            //Vérifier si le fichier est au bon format
                            if (in_array($imageFile->guessExtension(), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {
                                //Déplacer le fichier dans le dossier déclaré sous le nom files_directory dans services.yaml
                                $imageFile->move($_ENV['PATH_FILES'], $imageFileName);
                                //Sauvegarder le contenu dans la base de données
                                $portfolio->setBanniere($_ENV['SRC_FILES'] . '/' . $imageFileName);
                            } elseif (!in_array($imageFile->guessExtension(), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {
                                $this->addFlash('danger', 'L\'image doit être au format jpg, jpeg, png, gif, svg ou webp');
                            }
                        } elseif ($portfolio->getBanniere() != $_ENV['SRC_FILES'] . '/banniere.jpg') {
                            $portfolio->setBanniere($portfolio->getBanniere());
                        } else {
                            $portfolio->setBanniere($_ENV['SRC_FILES'] . '/banniere.jpg');
                        }

                        if ($form->get('optSearch')->getData() === true) {
                            $portfolio->setOptSearch(true);
                        } elseif ($form->get('optSearch')->getData() === false) {
                            $portfolio->setOptSearch(false);
                        }

                        if ($form->get('visibilite')->getData() === true) {
                            $portfolios = $portfolioRepository->findBy(['annee' => $portfolio->getAnnee(), 'etudiant' => $data_user->getUser()->getEtudiant()]);
                            foreach ($portfolios as $otherportfolio) {
                                $otherportfolio->setVisibilite(false);
                                $portfolioRepository->save($otherportfolio, true);
                            }
                            $portfolio->setVisibilite(true);
                        } elseif ($form->get('visibilite')->getData() === false) {
                            $portfolio->setVisibilite(false);
                        }

                        $portfolio->setDateModification(new \DateTime());
                        $portfolioRepository->save($portfolio, true);
                        return $this->redirectToRoute('app_portfolio_process_step', [
                            'id' => $portfolio->getId(),
                            'step' => 'portfolio',
                            'data_user' => $data_user,
                        ]);
                    }

                    $errors = $form->getErrors(true, true);
                    $errorsOutput = [];
                    foreach ($errors as $error) {
                        if ($error->getOrigin()) {
                            $errorsOutput[] = [
                                'field' => $error->getOrigin()->getName(), 'message' => $error->getMessage()
                            ];
                        } else {
                            $errorsOutput[] = [
                                'message' => $error->getMessage()
                            ];
                        }
                    }

                    return $this->json(['success' => false, 'errors' => $errorsOutput], 500);

                case 'addPage':
                    $etudiant = $this->getUser()->getEtudiant();
                    $biblio = $bibliothequeRepository->findOneBy(['etudiant' => $etudiant]);
                    $page = $pageRepository->findOneBy(['id' => $request->query->get('page')]);
                    $ordrePage = $ordrePageRepository->findOneBy(['page' => $page, 'portfolio' => $portfolio]);
                    $allTraces = $biblio->getTraces();
                    $traces = [];
                    foreach ($allTraces as $trace) {
                        $ordreTrace = $ordreTraceRepository->findOneBy(['trace' => $trace, 'page' => $page]);
                        if (!$ordreTrace) {
                            $traces[] = $trace;
                        }
                    }

                    if ($ordrePage !== null) {
                        if ($ordrePage->getOrdre() == 1) {
                            $ordreMin = true;
                            $ordreMax = $portfolio->getOrdrePages()->count();
                        } else {
                            $ordreMin = false;
                            $ordreMax = $portfolio->getOrdrePages()->count();
                        }
                    }

                    if ($page) {
                        $ordreTrace = $ordreTraceRepository->findBy(['page' => $page]);
                        $existingTraces = [];
                        foreach ($ordreTrace as $ordre) {
                            $existingTraces[] = $ordre->getTrace();
                        }
                        $portfolio->addPage($page);
                        $pageRepository->save($page, true);
                    } else {
                        $page = new Page();
                        $page->setIntitule('Nouvelle page');
                        $page->setDescription('Chapô de ma page');
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
                            'data_user' => $data_user,
                        ]);
                    }

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
                        'data_user' => $data_user,
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
                        'data_user' => $data_user,
                    ]);

                case 'editPage':
                    $etudiant = $this->getUser()->getEtudiant();
                    $biblio = $bibliothequeRepository->findOneBy(['etudiant' => $etudiant]);
                    $traces = $biblio->getTraces();

                    $page = $pageRepository->findOneBy(['id' => $request->query->get('page')]);
                    $ordrePage = $ordrePageRepository->findOneBy(['page' => $page, 'portfolio' => $portfolio]);
                    $ordreTrace = $ordreTraceRepository->findBy(['page' => $page]);
                    $existingTraces = [];
                    foreach ($ordreTrace as $ordre) {
                        $existingTraces[] = $ordre->getTrace();
                    }
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
                            'data_user' => $data_user,
                        ]);
                    }

                    $errors = $form->getErrors(true, true);
                    $errorsOutput = [];
                    foreach ($errors as $error) {
                        if ($error->getOrigin()) {
                            $errorsOutput[] = [
                                'field' => $error->getOrigin()->getName(), 'message' => $error->getMessage()
                            ];
                        } else {
                            $errorsOutput[] = [
                                'message' => $error->getMessage()
                            ];
                        }
                    }

                    return $this->json(['success' => false, 'errors' => $errorsOutput], 500);

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
                    $pageRepository->remove($page, true);

                    return $this->redirectToRoute('app_portfolio_process_step', [
                        'id' => $id,
                        'step' => 'portfolio',
                        'data_user' => $data_user,
                    ]);

                case 'addTrace':
                    $trace = $traceRepository->findOneBy(['id' => $request->query->get('trace')]);
                    $page = $pageRepository->findOneBy(['id' => $request->query->get('page')]);


                    $user = $security->getUser()->getEtudiant();

                    $biblio = $bibliothequeRepository->findOneBy(['etudiant' => $user]);

                    if (!$trace) {
                        $trace = new Trace();
                        $trace->setTitre('Nouvelle trace');
                        $trace->setBibliotheque($biblio);
                    }

                    $ordreTraces = $ordreTraceRepository->findBy(['page' => $page]);
                    $ordre = count($ordreTraces) + 1;


                    $newOrdreTrace = new OrdreTrace();
                    $newOrdreTrace->setOrdre($ordre);
                    $newOrdreTrace->setTrace($trace);
                    $newOrdreTrace->setPage($page);
                    $page->addOrdreTrace($newOrdreTrace);
                    $ordreTraceRepository->save($newOrdreTrace, true);

                    $traceRepository->save($trace, true);

                    $typesNewTrace = $traceRegistry->getTypeTraces();

                    if ($trace->getTypeTrace() == null) {
                        return $this->redirectToRoute('app_portfolio_process_step', [
                            'id' => $id,
                            'step' => 'editTrace',
                            'page' => $page->getId(),
                            'trace' => $trace->getId(),
                            'typesNewTrace' => $typesNewTrace,
                            'data_user' => $data_user,
                        ]);
                    } else {
                        return $this->redirectToRoute('app_portfolio_process_step', [
                            'id' => $id,
                            'step' => 'addPage',
                            'page' => $page->getId(),
                            'data_user' => $data_user,
                        ]);
                    }

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
                        'data_user' => $data_user,
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
                        'data_user' => $data_user,
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
                            'data_user' => $data_user,
                        ]);
                    }
                    break;

                case 'formTrace':
                    $trace = $traceRepository->findOneBy(['id' => $request->query->get('trace')]);
                    $type = $request->query->get('type');

                    $page = $ordreTraceRepository->findOneBy(['trace' => $trace])->getPage();

                    $traceType = $traceRegistry->getTypeTrace($type);

                    $user = $security->getUser()->getEtudiant();
                    $dept = $this->dataUserSession->getDepartement();
                    $semestre = $user->getSemestre();
                    $annee = $semestre->getAnnee();
                    $parcours = null;

                    $groupe = $user->getGroupe();
                    foreach ($groupe as $g) {
                        if ($g->getTypeGroupe()->getType() === 'TD') {
                            $parcours = $g->getApcParcours();
                        }
                    }

                    if ($parcours === null) {
                        $referentiel = $dept->getApcReferentiels();

                        $competences = $competenceRepository->findBy(['referentiel' => $referentiel->first()]);

                        foreach ($competences as $competence) {
                            $niveaux[] = $apcNiveauRepository->findByAnnee($competence, $annee->getOrdre());
                            foreach ($niveaux as $niveau) {
                                foreach ($niveau as $niv) {
                                    $competencesNiveau[] = $niv->getLibelle();
                                }
                            }
                        }
                    } else {
                        $niveaux = $apcNiveauRepository->findByAnneeParcours($annee, $parcours);
                        foreach ($niveaux as $niveau) {
                            $competencesNiveau[] = $niveau->getLibelle();
                        }
                    }

                    $form = $this->createForm($traceType::FORM, $trace, ['user' => $user, 'competences' => $competencesNiveau]);

                    $existingCompetences = [];
                    foreach ($trace->getValidations() as $validation) {
                        $existingCompetences[] = $validation->getApcNiveau()->getLibelle();
                    }

                    // Pré remplissage du formulaire
                    $form->get('competences')->setData($existingCompetences);

                    $trace->setTypetrace($type);

                    break;

                case 'deleteTrace':
                    $trace = $traceRepository->findOneBy(['id' => $request->query->get('trace')]);
                    $page = $pageRepository->findOneBy(['id' => $request->query->get('page')]);
                    $ordreTrace = $ordreTraceRepository->findOneBy(['trace' => $trace, 'page' => $page]);

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
                    if ($trace->getTypeTrace() == null) {
                        $traceRepository->remove($trace, true);
                    }

                    return $this->redirectToRoute('app_portfolio_process_step', [
                        'id' => $id,
                        'step' => 'addPage',
                        'page' => $page->getId(),
                        'data_user' => $data_user,
                    ]);

                case 'saveTrace':
                    $trace = $traceRepository->findOneBy(['id' => $request->query->get('trace')]);
                    $type = $request->query->get('type');

                    $traceType = $traceRegistry->getTypeTrace($type);

                    $page = $ordreTraceRepository->findOneBy(['trace' => $trace])->getPage()->getId();

                    $user = $security->getUser()->getEtudiant();
                    $dept = $this->dataUserSession->getDepartement();
                    $semestre = $user->getSemestre();
                    $annee = $semestre->getAnnee();
                    $parcours = null;

                    $groupe = $user->getGroupe();
                    foreach ($groupe as $g) {
                        if ($g->getTypeGroupe()->getType() === 'TD') {
                            $parcours = $g->getApcParcours();
                        }
                    }

                    if ($parcours === null) {
                        $referentiel = $dept->getApcReferentiels();

                        $competences = $competenceRepository->findBy(['referentiel' => $referentiel->first()]);

                        foreach ($competences as $competence) {
                            $niveaux[] = $apcNiveauRepository->findByAnnee($competence, $annee->getOrdre());
                            foreach ($niveaux as $niveau) {
                                foreach ($niveau as $niv) {
                                    $competencesNiveau[] = $niv->getLibelle();
                                }
                            }
                        }
                    } else {
                        $niveaux = $apcNiveauRepository->findByAnneeParcours($annee, $parcours);
                        foreach ($niveaux as $niveau) {
                            $competencesNiveau[] = $niveau->getLibelle();
                        }
                    }


                    $form = $this->createForm($traceType::FORM, $trace, ['user' => $user, 'competences' => $competencesNiveau]);
                    $form->handleRequest($request);
                    if (
                        $form->isSubmitted() && $form->isValid()
                    ) {
                        $existingCompetences = [];
                        foreach ($trace->getValidations() as $validation) {
                            $existingCompetences[] = $validation->getApcNiveau()->getLibelle();
                        }

                        $competencesForm = $form->get('competences')->getData();

                        $competences = $apcNiveauRepository->findBy(['libelle' => $competencesForm]);

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
                                if (!in_array($validation->getApcNiveau()->getLibelle(), $competencesForm)) {
                                    $validation->getApcNiveau()->removeValidation($validation);
                                    $trace->removeValidation($validation);
                                    $validationRepository->remove($validation);
                                }
                            }
                        }

                        if (
                            $trace->getTypetrace() == TraceTypeImage::class
                        ) {
                            if (isset($request->request->All()['img'])) {
                                $existingContenu = $request->request->All()['img'];
                            } else {
                                $existingContenu = null;
                            }
                        } elseif (
                            $trace->getTypetrace() == TraceTypePdf::class
                        ) {
                            if (isset($request->request->All()['pdf'])) {
                                $existingContenu = $request->request->All()['pdf'];
                            } else {
                                $existingContenu = null;
                            }
                        } elseif (
                            $trace->getTypetrace() == TraceTypeLien::class
                        ) {
                            if (isset($request->request->All()['trace_type_lien']['contenu'])) {
                                $existingContenu = $request->request->All()['trace_type_lien']['contenu'];
                            } else {
                                $existingContenu = null;
                            }
                        } elseif (
                            $trace->getTypetrace() == TraceTypeVideo::class
                        ) {
                            if (isset($request->request->All()['trace_type_video']['contenu'])) {
                                $existingContenu = $request->request->All()['trace_type_video']['contenu'];
                            } else {
                                $existingContenu = null;
                            }
                        } else {
                            $existingContenu = null;
                        }

                        $result = $traceType->save($form, $trace, $traceRepository, $traceRegistry, $existingContenu);

                        if ($result['success']) {
                            $form->getData()->setDatemodification(new \DateTimeImmutable());
                            $trace->setTypeTrace($type);
                            $traceRepository->save($trace, true);
                        } else {
                            $errorsOutput = [];

                            // Ajout de l'erreur retournée par la fonction save()
                            if (isset($result['error'])) {
                                // Spécifiez 'contenu' comme le champ d'origine de l'erreur.
                                $errorsOutput[] = [
                                    'field' => 'contenu',
                                    'message' => $result['error']
                                ];
                            }

                            return $this->json(['success' => false, 'errors' => $errorsOutput], 500);
                        }

                        return $this->redirectToRoute('app_portfolio_process_step', [
                            'id' => $id,
                            'step' => 'addPage',
                            'page' => $page,
                            'data_user' => $data_user,
                        ]);
                    }

                    $errors = $form->getErrors(true, true);
                    $errorsOutput = [];
                    foreach ($errors as $error) {
                        if ($error->getOrigin()) {
                            $errorsOutput[] = [
                                'field' => $error->getOrigin()->getName(), 'message' => $error->getMessage()
                            ];
                        } else {
                            $errorsOutput[] = [
                                'message' => $error->getMessage()
                            ];
                        }
                    }

                    return $this->json(['success' => false, 'errors' => $errorsOutput], 500);

                case 'selectedCv':
                    $cv = $cvRepository->findOneBy(['id' => $request->query->get('cv')]);
                    if ($cv == null) {
                        $cv = new Cv();
                        $cv->setIntitule('Nouveau CV');
                        $cv->setDateCreation(new \DateTimeImmutable());
                        $cv->setDescription('Description de mon CV');
                        $cv->setSoftSkills([]);
                        $cv->setHardSkills([]);
                        $cv->setLangues([]);
                        $cv->setReseaux([]);
                        $cv->setEtudiant($etudiant);
                        $portfolio->setCv($cv);
                    } else {
                        $portfolioCv = $portfolioRepository->findOneBy(['cv' => $cv]);
                        if ($portfolioCv) {
                            $portfolioCv->setCv(null);
                            $portfolioRepository->save($portfolioCv, true);
                        }
                        $portfolio->setCv($cv);
                    }

                    $cvRepository->save($cv, true);
                    $portfolioRepository->save($portfolio, true);

                    return $this->redirectToRoute('app_portfolio_process_step', [
                        'id' => $id,
                        'step' => 'addCv',
                        'cv' => $cv->getId(),
                        'data_user' => $data_user,
                    ]);

                case 'deleteCv':
                    $portfolio->setCv(null);
                    $portfolioRepository->save($portfolio, true);

                    return $this->redirectToRoute('app_portfolio_process_step', [
                        'id' => $id,
                        'step' => 'addCv',
                        'data_user' => $data_user,
                    ]);

                case 'editCv':
                    $cv = $cvRepository->findOneBy(['id' => $request->query->get('cv')]);

                    $form = $this->createForm(CvType::class, $cv);

                    break;

                case 'saveCv':
                    $cv = $cvRepository->findOneBy(['id' => $request->query->get('cv')]);

                    $form = $this->createForm(CvType::class, $cv);

                    $form->handleRequest($request);

                    if ($form->isSubmitted() && $form->isValid()) {
                        $cv = $cvRepository->findOneBy(['id' => $request->query->get('cv')]);

                        // récupérer les données du formulaire
                        $cvData = $form->getData();
                        $experiences = $cvData->getExperience();
                        $formations = $cvData->getFormation();

                        $experienceIds = [];
                        $formationIds = [];

                        foreach ($experiences as $experience) {
                            // Ajouter l'expérience dans le CV que si elle n'est pas déjà liée.
                            if (!$cv->getExperience()->contains($experience)) {
                                $cv->addExperience($experience);
                            }
                            // Ajouter l'ID de l'expérience dans le tableau.
                            $experienceIds[] = $experience->getId();
                        }

                        foreach ($formations as $formation) {
                            // Ajouter la formation dans le CV que si elle n'est pas déjà liée.
                            if (!$cv->getFormation()->contains($formation)) {
                                $cv->addFormation($formation);
                            }
                            // Ajouter l'ID de la formation dans le tableau.
                            $formationIds[] = $formation->getId();
                        }

// Trouver les expériences et formations dans le CV qui ne sont pas incluses dans le formulaire.

                        foreach ($cv->getExperience() as $experience) {
                            if (!in_array($experience->getId(), $experienceIds)) {
                                // Supprimer l'expérience du CV et de la base de données.
                                $cv->removeExperience($experience);
                                $experienceRepository->remove($experience);
                            }
                        }

                        foreach ($cv->getFormation() as $formation) {
                            if (!in_array($formation->getId(), $formationIds)) {
                                // Supprimez la formation du CV et de la base de données.
                                $cv->removeFormation($formation);
                                $formationRepository->remove($formation);
                            }
                        }


                        $cv->setIntitule($cvData->getIntitule());
                        $cv->setDateModification(new \DateTimeImmutable());
                        $cv->setDescription($cvData->getDescription());
                        $cv->setLangues($cvData->getLangues());
                        $cv->setSoftSkills($cvData->getSoftSkills());
                        $cv->setHardSkills($cvData->getHardSkills());
                        $cv->setPoste($cvData->getPoste());
                        $cv->setReseaux($cvData->getReseaux());

                        $cvRepository->save($cv, true);

                        return $this->redirectToRoute('app_portfolio_process_step', [
                            'id' => $id,
                            'step' => 'addCv',
                            'cv' => $cv->getId(),
                            'data_user' => $data_user,
                        ]);
                    }

                    $errors = $form->getErrors(true, true);
                    $errorsOutput = [];
                    foreach ($errors as $error) {
                        if ($error->getOrigin()) {
                            $errorsOutput[] = [
                                'field' => $error->getOrigin()->getName(), 'message' => $error->getMessage()
                            ];
                        } else {
                            $errorsOutput[] = [
                                'message' => $error->getMessage()
                            ];
                        }
                    }

                    return $this->json(['success' => false, 'errors' => $errorsOutput], 500);
            }

            return $this->render('portfolio_process/step/_step.html.twig', [
                'step' => $step,
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
                'ordreMinTrace' => $ordreMinTrace ?? null,
                'error' => $error ?? null,
                'data_user' => $data_user,
                'cvs' => $cvs ?? null,
                'cv' => $cv ?? null,
            ]);
        } else {
            return $this->render('security/accessDenied.html.twig');
        }
    }
}
