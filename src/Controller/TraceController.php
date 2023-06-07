<?php

namespace App\Controller;

use App\Components\Trace\TraceRegistry;
use App\Components\Trace\TypeTrace\TraceTypeImage;
use App\Components\Trace\TypeTrace\TraceTypePdf;
use App\Entity\Page;
use App\Entity\Trace;
use App\Entity\Validation;
use App\Form\CompetenceType;
use App\Repository\ApcNiveauRepository;
use App\Repository\BibliothequeRepository;
use App\Repository\CompetenceRepository;
use App\Repository\PageRepository;
use App\Repository\TraceRepository;
use App\Repository\ValidationRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Service\Attribute\Required;

class TraceController extends BaseController
{

    public function __construct(
        protected TraceRepository     $traceRepository,
        public BibliothequeRepository $bibliothequeRepository,
        #[Required] public Security   $security
    )
    {
    }

    #[Route('/trace', name: 'app_trace')]
    public function index(
        TraceRegistry $traceRegistry,
    ): Response
    {

        $this->denyAccessUnlessGranted(
            'ROLE_ETUDIANT'
        );

        return $this->render('trace/index.html.twig', [
            'traces' => $traceRegistry->getTypeTraces(),
        ]);
    }

    #[Route('/trace/formulaire/{id}', name: 'app_trace_new')]
    public function new(
        Request              $request,
        TraceRepository      $traceRepository,
        TraceRegistry        $traceRegistry,
        CompetenceRepository $competenceRepository,
        ApcNiveauRepository  $apcNiveauRepository,
        Security             $security,
        string               $id,
    ): Response
    {
        $this->denyAccessUnlessGranted(
            'ROLE_ETUDIANT'
        );
        //En fonction du paramètre (et donc du choix de type de trace), on récupère l'objet de la classe TraceTypeImage ou TraceTypeLien ou ... qui contient toutes les informations de ce type de trace (FORM, class, ICON, save...)
        $traceType = $traceRegistry->getTypeTrace($id);

        $user = $security->getUser()->getEtudiant();

        $semestre = $user->getSemestre();
        $annee = $semestre->getAnnee();

        $dept = $this->dataUserSession->getDepartement();

        $referentiel = $dept->getApcReferentiels();

        $competences = $competenceRepository->findBy(['referentiel' => $referentiel->first()]);

//        dd($competences);

        foreach ($competences as $competence) {
            $niveaux[] = $apcNiveauRepository->findByAnnee($competence, $annee->getOrdre());
        }

        foreach ($niveaux as $niveau) {
            foreach ($niveau as $niv) {
                $competencesNiveau[] = $niv->getCompetences()->getLibelle();
            }
        }

//        dd($competencesNiveau);

        $trace = new Trace();
        $form = $this->createForm($traceType::FORM, $trace, ['user' => $user, 'competences' => $competencesNiveau]);
        $trace->setTypetrace($id);
        $traces = $traceRepository->findBy(['bibliotheque' => $this->bibliothequeRepository->findOneBy(['etudiant' => $user])]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

//            dd($form->get('competences')->getData());

            $competencesForm = $form->get('competences')->getData();
            $competences = $competenceRepository->findBy(['libelle' => $competencesForm]);

            foreach ($competences as $competence) {
                $validation = new Validation();
                $validation->setEtat(0);
                $competence->addValidation($validation);
                $trace->addValidation($validation);
            }

            if ($traceType->save($form, $trace, $traceRepository, $traceRegistry)['success']) {

                //Récupérer l'ordre saisi dans le form
                $ordreSaisi = $form->get('ordre')->getData();
//                dd($ordreSaisi);
                //Pour chaque page
                foreach ($traces as $traceStock) {
                    //Récupérer l'ordre de la trace
                    $ordre = $traceStock->getOrdre();
//            dd($ordre);
                    //Si l'ordre saisi est égal à l'ordre de la trace
                    if ($ordre === $ordreSaisi && $traceStock !== $trace) {
                        // Attribuer l'ordre saisi à la trace en cours d'édition
                        $trace->setOrdre($ordreSaisi);
                        //Attribuer l'ordre qui se trouve en dernière position du tableau de choices à la trace en cours de boucle
                        $traceStock->setOrdre(count($traces) + 1);
                    }
                }
                //Lier la trace à la Bibliotheque de l'utilisateur connecté
                $biblio = $this->bibliothequeRepository->findOneBy(['etudiant' => $this->getUser()->getEtudiant()]);
                $trace->setBibliotheque($biblio);

//                $trace->addValidation($validation);

                $traceRepository->save($trace, true);
                $this->addFlash('success', 'La trace a été enregistrée avec succès.');
                return $this->redirectToRoute('app_trace');
            } else {
                $error = $traceType->save($form, $trace, $traceRepository, $traceRegistry)['error'];
                $this->addFlash('error', $error);
            }
        }
        return $this->render('trace/formTrace.html.twig', [
            'form' => $form->createView(),
            'trace' => $traceRegistry->getTypeTrace($id),
            'competences' => $competencesNiveau,
        ]);
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

        $this->denyAccessUnlessGranted(
            'ROLE_ETUDIANT'
        );

        $trace = $traceRepository->find($id);
        $user = $security->getUser()->getEtudiant();


        $semestre = $user->getSemestre();
        $annee = $semestre->getAnnee();

        $dept = $this->dataUserSession->getDepartement();

        $referentiel = $dept->getApcReferentiels();

        $competences = $competenceRepository->findBy(['referentiel' => $referentiel->first()]);

//        dd($competences);

        foreach ($competences as $competence) {
            $niveaux[] = $apcNiveauRepository->findByAnnee($competence, $annee->getOrdre());
        }

        foreach ($niveaux as $niveau) {
            foreach ($niveau as $niv) {
                $competencesNiveau[] = $niv->getCompetences()->getLibelle();
            }
        }

        if (!$trace) {
            throw $this->createNotFoundException('Trace non trouvée.');
        }

        $traceType = $traceRegistry->getTypeTrace($trace->getTypetrace());
        $competence = $competenceRepository->findAll();
        $traces = $traceRepository->findBy(['bibliotheque' => $this->bibliothequeRepository->findOneBy(['etudiant' => $user])]);

        $form = $this->createForm($traceType::FORM, $trace, ['user' => $user, 'competences' => $competencesNiveau]);

        $existingCompetences = [];
        foreach ($trace->getValidations() as $validation) {
            $existingCompetences[] = $validation->getCompetences()->getLibelle();
        }

        // Pré remplissage du formulaire
        $form->get('competences')->setData($existingCompetences);

        $ordreOrigine = $trace->getOrdre();

        //Récupérer les images existantes dans la db
        $FileOrigine = $trace->getContenu();


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

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
                if ($request->request->get('contenu') == null) {
                    if (!isset($request->request->All()['img']) && $form->get('contenu')->getData() == null) {
                        $this->addFlash('error', 'Aucun fichier n\'a été sélectionné');
                        return $this->redirectToRoute('app_trace_edit', ['id' => $trace->getId()]);
                    } elseif (isset($request->request->All()['img'])) {
//                        $this->addFlash('success', 'HELLO');
                        $existingImages = $request->request->All()['img'];
                        $trace->setContenu(array_intersect($existingImages, $FileOrigine));
                    } elseif ($form->get('contenu')->getData() !== null && !isset($request->request->All()['img'])) {
//                        dd($form->get('contenu')->getData());
                        $trace->setContenu($request->request->get('contenu'));
                    }
                } else {
                    $existingImages = $request->request->All()['img'];
                    $trace->setContenu(array_intersect($existingImages, $FileOrigine));
                }
            } elseif ($trace->getTypetrace() == TraceTypePdf::class
            ) {
                if ($request->request->get('contenu') == null) {
                    if (!isset($request->request->All()['pdf']) && $form->get('contenu')->getData() == null) {
                        $this->addFlash('error', 'Aucun fichier n\'a été sélectionné');
                        return $this->redirectToRoute('app_trace_edit', ['id' => $trace->getId()]);
                    } elseif (isset($request->request->All()['pdf'])) {
//                        $this->addFlash('success', 'HELLO');
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

            if ($traceType->save($form, $trace, $traceRepository, $traceRegistry)['success']) {

                //Récupérer l'ordre saisi dans le form
                $ordreSaisi = $form->get('ordre')->getData();
//                dd($ordreSaisi);

                //Pour chaque trace
                foreach ($traces as $traceStock) {
                    //Récupérer l'ordre de la trace
                    $ordre = $traceStock->getOrdre();
//            dd($ordre);
                    //Si l'ordre saisi est égal à l'ordre de la trace
                    if ($ordre === $ordreSaisi && $traceStock !== $trace) {
                        // Attribuer l'ordre saisi à la trace en cours d'édition
                        $trace->setOrdre($ordreSaisi);
                        //Attribuer l'ordre de la trace en cours d'édition à la trace en cours de boucle
                        $traceStock->setOrdre($ordreOrigine);
                    }
                }

                $form->getData()->setDatemodification(new \DateTimeImmutable());
//                dump($trace);
//                die();
                $traceRepository->save($trace, true);
                $this->addFlash('success', 'La trace a été modifiée avec succès.');
                return $this->redirectToRoute('app_trace');
            } else {
                $error = $traceType->save($form, $trace, $traceRepository, $traceRegistry)['error'];
                $this->addFlash('error', $error);
            }
        }

        return $this->render('trace/formTrace.html.twig', [
            'form' => $form->createView(),
            'trace' => $trace,
            'competences' => $competence,
        ]);
    }

    #[
        Route('/trace/delete/{id}', name: 'app_trace_delete')]
    public function delete(
        Request         $request,
        TraceRepository $traceRepository,
        int             $id,
    ): Response
    {

        $this->denyAccessUnlessGranted(
            'ROLE_ETUDIANT'
        );

        $trace = $traceRepository->find($id);
        $type = $trace->getTypetrace();

        //Si la trace est de type image ou pdf, il faut supprimer le fichier
        if ($type == 'App\Components\Trace\TypeTrace\TraceTypeImage' || $type == 'App\Components\Trace\TypeTrace\TraceTypePdf') {
            $document = $trace->getContenu();
//                dd($document);
            foreach ($document as $doc) {
                unlink($doc);
            }
        }

        $traceRepository->remove($trace, true);
        $this->addFlash('success', 'La trace a été supprimée avec succès.');
        return $this->redirectToRoute('app_trace');
    }

    #[Route('/trace/page/{id}', name: 'app_add_trace_to_page')]
    public function addToPage(
        Request        $request,
        PageRepository $pageRepository,
        int            $id,
    ): Response
    {

        $this->denyAccessUnlessGranted(
            'ROLE_ETUDIANT'
        );

        //Récupérer la bibliothèque de l'utilisateur connecté
        $etudiant = $this->security->getUser()->getEtudiant();
        $biblio = $this->bibliothequeRepository->findOneBy(['etudiant' => $etudiant]);

        // Récupérer les traces de la bibliothèque
        $traces = $biblio->getTraces();

        // Récupérer les pages associées aux traces (donc les pages de l'étudiant connecté)
        $pages = [];
        foreach ($traces as $trace) {
            $pages = array_merge($pages, $trace->getPages()->toArray());
//            Si deux pages sont les mêmes, ne les afficher qu'une seule fois
            $pages = array_unique($pages, SORT_REGULAR);
        }

        $form = $this->createFormBuilder()
            ->add('pages', EntityType::class, [
                'class' => Page::class,
                'choices' => $pages,
                'choice_label' => 'intitule',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('Valider', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        // Récupérer la trace dont l'id est celui passé en paramètre
        $trace = $this->traceRepository->find($id);

        if ($form->isSubmitted() && $form->isValid()) {
//            Récupérer les pages sélectionnées
            $pages = $form->get('pages')->getData();
//            Ajouter la trace aux pages sélectionnées
            foreach ($pages as $page) {
                $page->addTrace($trace);
            }
//            dump($trace);
//            die();
            $this->addFlash('success', 'La trace a été ajoutée à la page avec succès.');
            $pageRepository->save($page, true);
            return $this->redirectToRoute('app_trace');
        }

        return $this->render('add_to_page.html.twig', [
            'form' => $form->createView(),
            'trace' => $trace,
        ]);
    }

    #[Route('/trace/show/{id}', name: 'app_trace_show')]
    public function show(
        int $id,
    ): Response
    {

        $this->denyAccessUnlessGranted(
            'ROLE_ETUDIANT'
        );

        $trace = $this->traceRepository->find($id);
        return $this->render('trace/show.html.twig', [
            'trace' => $trace,
        ]);
    }
}
