<?php

namespace App\Controller;

use App\Components\Trace\TraceRegistry;
use App\Entity\Trace;
use App\Repository\TraceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TraceBisController extends AbstractController
{
    #[Route('/trace/bis', name: 'app_trace_bis')]
    public function index(
        TraceRegistry $traceRegistry
    ): Response
    {
        return $this->render('trace_bis/index.html.twig', [
            'traces' => $traceRegistry->getTypeTraces(),
        ]);
    }

    #[Route('/trace/bis/formulaire/{id}', name: 'app_trace_bis_formulaire')]
    public function formulaire(
        Request $request,
        TraceRepository $traceRepository,
        TraceRegistry $traceRegistry,
        string $id
    ): Response
    {
        //En fonction du paramètre (et donc du choix de type de trace), on récupère l'objet de la classe TraceTypeImage ou TraceTypeLien ou ... qui contient toutes les informations de ce type de trace (FROM, class, ICON, save...)
        $traceType = $traceRegistry->getTypeTrace($id);
//dump($id);
//dump($traceType);
//die();
        $trace = new Trace();
        $form = $this->createForm($traceType::FORM, $trace);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $traceType->save($form, $trace, $traceRepository, 'files_directory');

            return $this->redirectToRoute('app_trace_bis');
        }


        return $this->render('trace_bis/formulaire.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
