<?php

namespace App\Controller;

use App\Repository\TraceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('enseignant')]
class BilanEvalController extends BaseController
{
    #[Route('/bilan/eval', name: 'app_bilan_eval')]
    public function index(): Response
    {
        $data_user = $this->dataUserSession;


        return $this->render('bilan_eval/index.html.twig', [
            'data_user' => $data_user,
        ]);
    }

    #[Route('/bilan/eval/trace/{id}', name: 'app_bilan_eval_trace')]
    public function traceShow(
        TraceRepository $traceRepository,
        int             $id
    ): Response
    {
        if ($this->isGranted('ROLE_ENSEIGNANT')) {

            $data_user = $this->dataUserSession;

            $trace = $traceRepository->find($id);


            return $this->render('bilan_eval/traceShow.html.twig', [
                'trace' => $trace,
                'data_user' => $data_user,
            ]);
        } else {
            return $this->render('security/accessDenied.html.twig');
        }
    }
}
