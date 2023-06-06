<?php

namespace App\Controller;

use App\Repository\BibliothequeRepository;
use App\Repository\EtudiantRepository;
use App\Repository\TraceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VueTraceController extends BaseController
{
    #[Route('//etudiant/{id}/trace', name: 'app_vue_trace')]
    public function index(
        BibliothequeRepository $bibliothequeRepository,
        TraceRepository        $traceRepository,
        EtudiantRepository     $etudiantRepository,
        int                    $id,
    ): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ENSEIGNANT');

        $etudiant = $etudiantRepository->findOneBy(['id' => $id]);

        $data_user = $this->dataUserSession;

        $biblio = $bibliothequeRepository->findBy([
            'etudiant' => $etudiant
        ]);

        $traces = $traceRepository->findBy([
            'bibliotheque' => $biblio
        ]);

        if (count($traces) > 1) {
            return $this->render('vue_trace/bibliotheque.html.twig', [
                'etudiant' => $etudiant,
                'traces' => $traces,
            ]);
        } elseif (count($traces) == 1) {
            return $this->render('vue_trace/bibliotheque.html.twig', [
                'etudiant' => $etudiant,
                'traces' => $traces,
            ]);
        } elseif (count($traces) == 0) {
            $this->addFlash('error', 'Aucune trace n\'a été trouvée pour cet étudiant');
        }

        return $this->render('dashboard_enseignant/index.html.twig', [
            'data_user' => $data_user,
        ]);
    }
}
