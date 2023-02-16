<?php

namespace App\Controller;

use App\Components\Trace\TraceRegistry;
use App\Entity\Trace;
use App\Form\TraceType;
use App\Repository\TraceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TraceController extends AbstractController
{
    #[Route('/trace/new', name: 'app_trace')]
    public function index(Request $request, TraceRegistry $traceRegistry, TraceRepository $traceRepository): Response
    {

//        $traceRegistry->buildForm($request, $traceRepository, $this->getParameter('images_directory'));

        $trace = new Trace();
        $form = $this->createForm(TraceType::class, $trace);
//
        $form->handleRequest($request);
//
//
        if ($form->isSubmitted() && $form->isValid()) {
//
//        dump($trace);
//        die();
//
//         Gérer manuellement l'upload de l'image
            $imageFile = $form['contenu']->getData();
            if ($imageFile) {
                $imageFileName = uniqid() . '.' . $imageFile->guessExtension();
                if (in_array($imageFile->guessExtension(), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {
                    $imageFile->move($this->getParameter('images_directory'), $imageFileName);
                    $trace->setContenu($this->getParameter('images_directory') . '/' . $imageFileName);
                    $traceRepository->save($trace, true);
                    return $this->redirectToRoute('app_trace');
                } else {
                    echo 'Le fichier n\'est pas au bon format';
                }
            }

//            $traceRegistry->getTypeTrace($trace->getTypeTrace())->buildForm();
//
//            $traceRegistry->getTypeTrace($trace->getTypeTrace())->save($form, $trace, $traceRepository, $this->getParameter('images_directory'));
        }

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
