<?php

namespace App\Controller;

use App\Components\Trace\TraceRegistry;
use App\Entity\Trace;
use App\Repository\BibliothequeRepository;
use App\Repository\CompetenceRepository;
use App\Repository\TraceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TraceController extends AbstractController
{
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
        string                 $id,
    ): Response
    {
        //En fonction du paramètre (et donc du choix de type de trace), on récupère l'objet de la classe TraceTypeImage ou TraceTypeLien ou ... qui contient toutes les informations de ce type de trace (FROM, class, ICON, save...)
        $traceType = $traceRegistry->getTypeTrace($id);
        //dump($id);
        //dump($traceType);
        //die();
        $competence = $competenceRepository->findAll();

        $trace = new Trace();
        $form = $this->createForm($traceType::FORM, $trace);
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

        return $this->render('trace/formulaire.html.twig', [
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
        string               $id,
        CompetenceRepository $competenceRepository,
    ): Response
    {

        //todo: dans formulaire edit : récupérer le fichier la trace à modifier et l'afficher dans le formulaire

        $trace = $traceRepository->find($id);

        if (!$trace) {
            throw $this->createNotFoundException('Trace non trouvée.');
        }

        $traceType = $traceRegistry->getTypeTrace($trace->getTypetrace());
        $competence = $competenceRepository->findAll();

        $form = $this->createForm($traceType::FORM, $trace);

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

        return $this->render('trace/edit.html.twig', [
            'form' => $form->createView(),
            'trace' => $trace,
            'competences' => $competence,
        ]);
    }

    #[Route('/trace/show/{typeTrace}', name: 'app_show')]
    public function show(
        TraceRegistry $traceRegistry,
        string        $typeTrace): Response
    {
        $trace = $traceRegistry->getTypeTrace($typeTrace);
        return $this->render('trace/show.html.twig', [
            'trace' => $trace,
        ]);
    }
}
