<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\Etudiant1Type;
use App\Repository\EtudiantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil/edit')]
class ProfilEditController extends AbstractController
{
    #[Route('/', name: 'app_profil_edit_index', methods: ['GET'])]
    public function index(EtudiantRepository $etudiantRepository): Response
    {
        return $this->render('profil_edit/index.html.twig', [
            'etudiants' => $etudiantRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_profil_edit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EtudiantRepository $etudiantRepository): Response
    {
        $etudiant = new Etudiant();
        $form = $this->createForm(Etudiant1Type::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etudiantRepository->save($etudiant, true);

            return $this->redirectToRoute('app_profil_edit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil_edit/new.html.twig', [
            'etudiant' => $etudiant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_profil_edit_show', methods: ['GET'])]
    public function show(Etudiant $etudiant): Response
    {
        return $this->render('profil_edit/show.html.twig', [
            'etudiant' => $etudiant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_profil_edit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Etudiant $etudiant, EtudiantRepository $etudiantRepository): Response
    {
        $form = $this->createForm(Etudiant1Type::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etudiantRepository->save($etudiant, true);

            return $this->redirectToRoute('app_profil_edit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil_edit/edit.html.twig', [
            'etudiant' => $etudiant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_profil_edit_delete', methods: ['POST'])]
    public function delete(Request $request, Etudiant $etudiant, EtudiantRepository $etudiantRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$etudiant->getId(), $request->request->get('_token'))) {
            $etudiantRepository->remove($etudiant, true);
        }

        return $this->redirectToRoute('app_profil_edit_index', [], Response::HTTP_SEE_OTHER);
    }
}
