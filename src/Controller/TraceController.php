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
use App\Entity\Page;
use App\Entity\Trace;
use App\Entity\Validation;
use App\Repository\ApcNiveauRepository;
use App\Repository\BibliothequeRepository;
use App\Repository\CompetenceRepository;
use App\Repository\NotificationRepository;
use App\Repository\OrdreTraceRepository;
use App\Repository\PageRepository;
use App\Repository\TraceRepository;
use App\Repository\ValidationRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Service\Attribute\Required;

#[Route('etudiant/bibliotheque')]
class TraceController extends BaseController
{
    public function __construct(
        protected TraceRepository     $traceRepository,
        public BibliothequeRepository $bibliothequeRepository,
        public CompetenceRepository   $competenceRepository,
        public ApcNiveauRepository    $apcNiveauRepository,
        #[Required] public Security   $security
    )
    {
    }

    #[Route('/trace', name: 'app_trace')]
    public function index(
        TraceRegistry $traceRegistry,
        Request       $request,
    ): Response
    {

        if ($this->isGranted('ROLE_ETUDIANT')) {
            $user = $this->security->getUser()->getEtudiant();

            $semestre = $user->getSemestre();
            $annee = $semestre->getAnnee();

            $dept = $this->dataUserSession->getDepartement();
            $parcours = null;

            $groupe = $user->getGroupe();
            foreach ($groupe as $g) {
                if ($g->getTypeGroupe()->getType() === 'TD') {
                    $parcours = $g->getApcParcours();
                }
            }

            if ($parcours === null) {
                $referentiel = $dept->getApcReferentiels();

                $competences = $this->competenceRepository->findBy(['referentiel' => $referentiel->first()]);

                foreach ($competences as $competence) {
                    $niveaux[] = $this->apcNiveauRepository->findByAnnee($competence, $annee->getOrdre());
                    foreach ($niveaux as $niveau) {
                        foreach ($niveau as $niv) {
                            $competencesNiveau[] = $niv->getLibelle();
                        }
                    }
                }
            } else {
                $niveaux = $this->apcNiveauRepository->findByAnneeParcours($annee, $parcours);
                foreach ($niveaux as $niveau) {
                    $competencesNiveau[] = $niveau->getLibelle();
                }
            }

            $competenceId = $request ? $request->query->get('competence') : null;
            $competence = $this->apcNiveauRepository->findOneBy(['id' => $competenceId]);

            return $this->render('trace/index.html.twig', [
                'typesTraces' => $traceRegistry->getTypeTraces(),
                'competences' => $competencesNiveau,
                'competence' => $competence,
                'data_user' => $this->dataUserSession,
            ]);
        } else {
            return $this->render('security/accessDenied.html.twig');
        }
    }

    #[Route('/trace/formulaire/{id}', name: 'app_trace_new')]
    public function new(
        Request         $request,
        TraceRepository $traceRepository,
        TraceRegistry   $traceRegistry,
        Security        $security,
        string          $id,
    ): Response
    {
        if ($this->isGranted('ROLE_ETUDIANT')) {
            $data_user = $this->dataUserSession;
            //En fonction du paramètre (et donc du choix de type de trace), on récupère l'objet de la classe TraceTypeImage ou TraceTypeLien ou ... qui contient toutes les informations de ce type de trace (FORM, class, ICON, save...)
            $traceType = $traceRegistry->getTypeTrace($id);

            $user = $security->getUser()->getEtudiant();

            $semestre = $user->getSemestre();
            $annee = $semestre->getAnnee();

            $dept = $this->dataUserSession->getDepartement();
            $parcours = null;

            $groupe = $user->getGroupe();
            foreach ($groupe as $g) {
                if ($g->getTypeGroupe()->getType() === 'TD') {
                    $parcours = $g->getApcParcours();
                }
            }

            if ($parcours === null) {
                $referentiel = $dept->getApcReferentiels();

                $competences = $this->competenceRepository->findBy(['referentiel' => $referentiel->first()]);

                foreach ($competences as $competence) {
                    $niveaux[] = $this->apcNiveauRepository->findByAnnee($competence, $annee->getOrdre());
                    foreach ($niveaux as $niveau) {
                        foreach ($niveau as $niv) {
                            $competencesNiveau[] = $niv->getLibelle();
                        }
                    }
                }
            } else {
                $niveaux = $this->apcNiveauRepository->findByAnneeParcours($annee, $parcours);
                foreach ($niveaux as $niveau) {
                    $competencesNiveau[] = $niveau->getLibelle();
                }
            }

            $trace = new Trace();
            $form = $this->createForm($traceType::FORM, $trace, ['user' => $user, 'competences' => $competencesNiveau]);
            $trace->setTypetrace($id);
            $traces = $traceRepository->findBy(['bibliotheque' => $this->bibliothequeRepository->findOneBy(['etudiant' => $user])]);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $competencesForm = $form->get('competences')->getData();
                $niveaux = $this->apcNiveauRepository->findBy(['libelle' => $competencesForm]);

                foreach ($niveaux as $niveau) {
                    $validation = new Validation();
                    $validation->setEtat(0);
                    $niveau->addValidation($validation);
                    $trace->addValidation($validation);
                }

                $existingTrace = null;

                if ($traceType->save($form, $trace, $traceRepository, $traceRegistry, $existingTrace)['success']) {
                    //Lier la trace à la Bibliotheque de l'utilisateur connecté
                    $biblio = $this->bibliothequeRepository->findOneBy(['etudiant' => $this->getUser()->getEtudiant()]);
                    $trace->setBibliotheque($biblio);

                    // utiliser htmlentities sur le champ description
                    $trace->setDescription(htmlentities($form->get('description')->getData()));
                    $traceRepository->save($trace, true);
                    $this->addFlash('success', 'La trace a été enregistrée avec succès.');
                    return $this->redirectToRoute('app_trace');
                } else {
                    $error = $traceType->save($form, $trace, $traceRepository, $traceRegistry, $existingTrace)['error'];
                    $this->addFlash('error', $error);
                    return $this->redirectToRoute('app_trace_new', ['id' => $id]);
                }
            }


            return $this->render('trace/formTrace.html.twig', [
                'form' => $form->createView(),
                'trace' => $traceRegistry->getTypeTrace($id),
                'competences' => $competencesNiveau,
                'data_user' => $data_user,
            ]);
        } else {
            return $this->render('security/accessDenied.html.twig');
        }
    }

    #[Route('/trace/edit/{id}', name: 'app_trace_edit')]
    public function edit(
        Request              $request,
        TraceRepository      $traceRepository,
        TraceRegistry        $traceRegistry,
        ApcNiveauRepository  $apcNiveauRepository,
        Security             $security,
        int                  $id,
        CompetenceRepository $competenceRepository,
        ValidationRepository $validationRepository,
    ): Response
    {

            $data_user = $this->dataUserSession;
            $trace = $traceRepository->find($id);
            $user = $security->getUser()->getEtudiant();

        if ($this->isGranted('ROLE_ETUDIANT') && $trace->getBibliotheque()->getEtudiant()->getId() == $user->getId()) {

//            dump($trace->getBibliotheque()->getEtudiant()->getId());
//            dump($user->getId());
//            die();
//            $this->denyAccessUnlessGranted($trace->getBibliotheque()->getEtudiant()->getId() == $user->getId());

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

                $competences = $this->competenceRepository->findBy(['referentiel' => $referentiel->first()]);

                foreach ($competences as $competence) {
                    $niveaux[] = $this->apcNiveauRepository->findByAnnee($competence, $annee->getOrdre());
                    foreach ($niveaux as $niveau) {
                        foreach ($niveau as $niv) {
                            $competencesNiveau[] = $niv->getLibelle();
                        }
                    }
                }
            } else {
                $niveaux = $this->apcNiveauRepository->findByAnneeParcours($annee, $parcours);
                foreach ($niveaux as $niveau) {
                    $competencesNiveau[] = $niveau->getLibelle();
                }
            }


            if (!$trace) {
                throw $this->createNotFoundException('Trace non trouvée.');
            }

            $traceType = $traceRegistry->getTypeTrace($trace->getTypetrace());
            $traces = $traceRepository->findBy(['bibliotheque' => $this->bibliothequeRepository->findOneBy(['etudiant' => $user])]);

            $form = $this->createForm($traceType::FORM, $trace, ['user' => $user, 'competences' => $competencesNiveau]);

            $existingCompetences = [];
            foreach ($trace->getValidations() as $validation) {
                $existingCompetences[] = $validation->getApcNiveau()->getLibelle();
            }

            // Pré remplissage du formulaire
            $form->get('competences')->setData($existingCompetences);

            $ordreOrigine = $trace->getOrdre();

            //Récupérer les images existantes dans la db
            $FileOrigine = $trace->getContenu();


            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
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
                    if ($request->request->get('contenu') == null) {
                        if (!isset($request->request->All()['img']) && $form->get('contenu')->getData() == null) {
                            $this->addFlash('error', 'Aucun fichier n\'a été sélectionné');
                            return $this->redirectToRoute('app_trace_edit', ['id' => $trace->getId()]);
                        } elseif (isset($request->request->All()['img'])) {
                            $existingImages = $request->request->All()['img'];
                            $trace->setContenu(array_intersect($existingImages, $FileOrigine));
                        } elseif ($form->get('contenu')->getData() !== null && !isset($request->request->All()['img'])) {
                            $trace->setContenu($request->request->get('contenu'));
                        }
                    } else {
                        $existingImages = $request->request->All()['img'];
                        $trace->setContenu(array_intersect($existingImages, $FileOrigine));
                    }
                } elseif (
                    $trace->getTypetrace() == TraceTypePdf::class
                ) {
                    if ($request->request->get('contenu') == null) {
                        if (!isset($request->request->All()['pdf']) && $form->get('contenu')->getData() == null) {
                            $this->addFlash('error', 'Aucun fichier n\'a été sélectionné');
                            return $this->redirectToRoute('app_trace_edit', ['id' => $trace->getId()]);
                        } elseif (isset($request->request->All()['pdf'])) {
                            $existingImages = $request->request->All()['pdf'];
                            $trace->setContenu(array_intersect($existingImages, $FileOrigine));
                        } elseif ($form->get('contenu')->getData() !== null && !isset($request->request->All()['pdf'])) {
//                        dd($form->get('contenu')->getData());
                            $trace->setContenu($request->request->get('contenu'));
                        }
                    } else {
                        $existingPdf = $request->request->All()['pdf'];
                        $trace->setContenu(array_intersect($existingPdf, $FileOrigine));
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
//                dd($request->request->All()['trace_type_video']['contenu']);
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
                    $traceRepository->save($trace, true);
                    $this->addFlash('success', 'La trace a été modifiée avec succès.');
                    return $this->redirectToRoute('app_trace');
                } else {
                    $error = $traceType->save($form, $trace, $traceRepository, $traceRegistry, $existingContenu)['error'];
                    $this->addFlash('error', $error);
                }
            }

            return $this->render('trace/formTrace.html.twig', [
                'form' => $form->createView(),
                'trace' => $trace,
//                'competences' => $competence,
                'data_user' => $data_user,
            ]);
        } else {
            return $this->render('security/accessDenied.html.twig');
        }
    }

    #[
        Route('/trace/delete/{id}', name: 'app_trace_delete')]
    public function delete(
        Request              $request,
        TraceRepository      $traceRepository,
        OrdreTraceRepository $ordreTraceRepository,
        int                  $id,
    ): Response
    {

            $trace = $traceRepository->find($id);
            $type = $trace->getTypetrace();
            $user = $this->security->getUser()->getEtudiant();

        if ($this->isGranted('ROLE_ETUDIANT') && $trace->getBibliotheque()->getEtudiant() == $user) {

            //Si la trace est de type image ou pdf, il faut supprimer le fichier
            if ($type == 'App\Components\Trace\TypeTrace\TraceTypeImage' || $type == 'App\Components\Trace\TypeTrace\TraceTypePdf') {
                $document = $trace->getContenu();
                foreach ($document as $doc) {
                    $doc = substr($doc, strrpos($doc, '/') + 1);
                    $doc = $_ENV['PATH_FILES'] . '/' . $doc;
                    unlink($doc);
                }
            }

            if ($trace->getOrdreTrace()) {
                $ordreTraceRepository->remove($trace->getOrdreTrace(), true);
            }

            $traceRepository->remove($trace, true);
            $this->addFlash('success', 'La trace a été supprimée avec succès.');
            return $this->redirectToRoute('app_trace');
        } else {
            return $this->render('security/accessDenied.html.twig');
        }
    }

    #[Route('/trace/show', name: 'app_trace_index')]
    public function indexShow(
        Request                $request,
        TraceRepository        $traceRepository,
        NotificationRepository $notificationRepository,
    ): Response
    {
        if ($this->isGranted('ROLE_ETUDIANT')) {
            $id = $request->query->get('id');
            $notificationId = $request->query->get('notification_id');

            if ($notificationId) {
                $notification = $notificationRepository->find($notificationId);
                if ($notification && !$notification->isLu()) {
                    $notification->setLu(true);
                    $notificationRepository->save($notification, true);
                }
            }

            $etudiant = $this->security->getUser()->getEtudiant();
            $trace = $traceRepository->findOneBy(['id' => $id]);

            return $this->render('trace/show.html.twig', [
                'step' => 'trace',
                'id' => $id,
                'trace' => $trace,
                'etudiant' => $etudiant,
                'data_user' => $this->dataUserSession,
            ]);
        } else {
            return $this->render('security/accessDenied.html.twig');
        }
    }
}
