<?php

namespace App\Controller;

use App\Components\Trace\TraceRegistry;
use App\Entity\Page;
use App\Entity\Trace;
use App\Repository\BibliothequeRepository;
use App\Repository\CompetenceRepository;
use App\Repository\PageRepository;
use App\Repository\TraceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Service\Attribute\Required;

class TraceController extends AbstractController
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

        return $this->render('trace/index.html.twig', [
            'traces' => $traceRegistry->getTypeTraces(),
        ]);
    }

    #[Route('/trace/formulaire/{id}', name: 'app_trace_new')]
    public function new(
        Request                $request,
        TraceRepository        $traceRepository,
        TraceRegistry          $traceRegistry,
        BibliothequeRepository $bibliothequeRepository,
        CompetenceRepository   $competenceRepository,
        Security       $security,
        string                 $id,
    ): Response
    {
        //En fonction du paramètre (et donc du choix de type de trace), on récupère l'objet de la classe TraceTypeImage ou TraceTypeLien ou ... qui contient toutes les informations de ce type de trace (FROM, class, ICON, save...)
        $traceType = $traceRegistry->getTypeTrace($id);
        //dump($id);
        //dump($traceType);
        //die();
        $competence = $competenceRepository->findAll();
        $user = $security->getUser()->getEtudiant();

        $trace = new Trace();
        $form = $this->createForm($traceType::FORM, $trace, ['user' => $user]);
        $trace->setTypetrace($id);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($traceType->save($form, $trace, $traceRepository, $traceRegistry)['success']) {

                //Lier la trace à la Bibliotheque de l'utilisateur connecté
                $biblio = $bibliothequeRepository->findOneBy(['etudiant' => $this->getUser()->getEtudiant()]);
                $trace->setBibliotheque($biblio);

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
            'competences' => $competence,
        ]);
    }

    #[Route('/trace/edit/{id}', name: 'app_trace_edit')]
    public function edit(
        Request              $request,
        TraceRepository      $traceRepository,
        TraceRegistry        $traceRegistry,
        Security       $security,
        int               $id,
        CompetenceRepository $competenceRepository,
    ): Response
    {

        //todo: dans formulaire edit : récupérer le fichier la trace à modifier et l'afficher dans le formulaire => donc convertion string -> file

        $trace = $traceRepository->find($id);
        $user = $security->getUser()->getEtudiant();

//        $contenus = $trace->getContenu();
//        foreach ($contenus as $contenu) {
//
//        }

        if (!$trace) {
            throw $this->createNotFoundException('Trace non trouvée.');
        }

        $traceType = $traceRegistry->getTypeTrace($trace->getTypetrace());
        $competence = $competenceRepository->findAll();

        $form = $this->createForm($traceType::FORM, $trace, ['user' => $user]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($traceType->save($form, $trace, $traceRepository, $traceRegistry)['success']) {
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

    #[Route('/trace/delete/{id}', name: 'app_trace_delete')]
    public function delete(
        Request         $request,
        TraceRepository $traceRepository,
        int          $id,
    ): Response
    {
        $trace = $traceRepository->find($id);

        $traceRepository->remove($trace, true);
        $this->addFlash('success', 'La trace a été supprimée avec succès.');
        return $this->redirectToRoute('app_trace');
    }

    #[Route('/trace/page/{id}', name: 'app_add_trace_to_page')]
    public function addToPage(
        Request         $request,
        PageRepository  $pageRepository,
        int          $id,
    ): Response
    {
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
        int                $id,
    ): Response
    {
        $trace = $this->traceRepository->find($id);
        return $this->render('trace/show.html.twig', [
            'trace' => $trace,
        ]);
    }
}
