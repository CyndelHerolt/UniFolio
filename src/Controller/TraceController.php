<?php

namespace App\Controller;

use App\Components\Trace\TraceRegistry;
use App\Components\Trace\TypeTrace\AbstractTrace;
use App\Components\Trace\TypeTrace\TraceTypeLien;
use App\Entity\Bibliotheque;
use App\Entity\Trace;
use App\Form\TraceType;
use App\Repository\BibliothequeRepository;
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

    #[Route('/trace/formulaire/{id}', name: 'app_trace_formulaire')]
    public function formulaire(
        Request $request,
        TraceRepository $traceRepository,
        TraceRegistry $traceRegistry,
        BibliothequeRepository $bibliothequeRepository,
        string $id,
    ): Response
    {
        //En fonction du paramètre (et donc du choix de type de trace), on récupère l'objet de la classe TraceTypeImage ou TraceTypeLien ou ... qui contient toutes les informations de ce type de trace (FROM, class, ICON, save...)
        $traceType = $traceRegistry->getTypeTrace($id);
//dump($id);
//dump($traceType);
//die();
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
            }
            else {
                $error = $traceType->save($form, $trace, $traceRepository, $traceRegistry)['error'];
                $this->addFlash('error', $error);
            }
        }

        return $this->render('trace/formulaire.html.twig', [
            'form' => $form->createView(),
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
