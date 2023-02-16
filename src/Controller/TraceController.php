<?php

namespace App\Controller;

use App\Components\Trace\TraceRegistry;
use App\Components\Trace\TypeTrace\AbstractTrace;
use App\Components\Trace\TypeTrace\TraceTypeImage;
use App\Components\Trace\TypeTrace\TraceTypeLien;
use App\Components\Trace\TypeTrace\TraceTypePdf;
use App\Components\Trace\TypeTrace\TraceTypeVideo;
use App\Entity\Trace;
use App\Form\TraceType;
use App\Repository\TraceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TraceController extends AbstractController
{
    #[Route('/trace', name: 'app_trace')]
    public function index(Request         $request,
                          TraceRegistry   $traceRegistry,
                          TraceRepository $traceRepository,
                          TraceTypeImage  $traceTypeImage
    ): Response
    {
//
//        $trace = new Trace();
//        $form = $this->createForm($traceRegistry->getChoiceTypeForm());

//        $forms = [];
//
//        // Pour chaque formulaire, créer une instance de formulaire en utilisant la constante FORM correspondante
//        foreach ($traceRegistry->getForms() as $type => $formClass) {
//            $form = $this->createForm($formClass);
//            $form->handleRequest($request);
//
//            // Ajouter le formulaire au tableau $forms pour l'afficher plus tard dans la vue
//            $forms[$type] = $form->createView();
//        }

//        $form = $this->createForm(TraceType::class, $trace);
////
//        $form->handleRequest($request);
////
////
//        if ($form->isSubmitted() && $form->isValid()) {
////
////        dump($trace);
////        die();
////
////         Gérer manuellement l'upload de l'image
//            $imageFile = $form['contenu']->getData();
//            if ($imageFile) {
//                $imageFileName = uniqid() . '.' . $imageFile->guessExtension();
//                if (in_array($imageFile->guessExtension(), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {
//                    $imageFile->move($this->getParameter('images_directory'), $imageFileName);
//                    $trace->setContenu($this->getParameter('images_directory') . '/' . $imageFileName);
//                    $traceRepository->save($trace, true);
//                    return $this->redirectToRoute('app_trace');
//                } else {
//                    echo 'Le fichier n\'est pas au bon format';
//                }
//            }
//
////            $traceRegistry->getTypeTrace($trace->getTypeTrace())->buildForm();
//
//            $traceRegistry->getTypeTrace($trace->getTypeTrace())->save($form, $trace, $traceRepository, $this->getParameter('images_directory'));
//        }

        return $this->render('trace/index.html.twig', [
            'traces' => $traceRegistry->getTypeTraces(),
//            'form' => $form->createView()
//        'forms' => $forms
        ]);
    }


    #[Route('/trace/image', name: 'trace_image')]
    public function imageForm(Request $request)
    {
        $formType = TraceTypeImage::FORM;
        $form = $this->createForm($formType);

        return $this->render('components/trace/form/trace_depot_image.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/trace/lien', name: 'trace_lien')]
    public function lienForm(Request $request)
    {
        $formType = TraceTypeLien::FORM;
        $form = $this->createForm($formType);

        return $this->render('components/trace/form/trace_depot_lien.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/trace/pdf', name: 'trace_pdf')]
    public function pdfForm(Request $request)
    {
        $formType = TraceTypePdf::FORM;
        $form = $this->createForm($formType);

        return $this->render('components/trace/form/trace_depot_pdf.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/trace/video', name: 'trace_video')]
    public function videoForm(Request $request)
    {
        $formType = TraceTypeVideo::FORM;
        $form = $this->createForm($formType);

        return $this->render('components/trace/form/trace_depot_video.html.twig', [
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
