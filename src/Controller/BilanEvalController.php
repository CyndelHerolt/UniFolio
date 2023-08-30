<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
