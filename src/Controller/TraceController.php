<?php

namespace App\Controller;

use App\Components\Trace\TraceRegistry;
use App\Entity\Trace;
use App\Form\TraceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TraceController extends AbstractController
{
    #[Route('/trace', name: 'app_trace')]
    public function index(TraceRegistry $traceRegistry): Response
    {
//        todo:if (tel type de trace est sélectionné => afficher tel type de méthode de récupération du contenu)
        $trace = new Trace();
        $form = $this->createForm(TraceType::class, $trace);

//        if ($form->isSubmitted() && $form->isValid()) {
//
//        }

        return $this->render('trace/index.html.twig', [
            'traces' => $traceRegistry->getTypeTraces(),
            'form' => $form->createView()
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
