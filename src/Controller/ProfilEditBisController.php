<?php

namespace App\Controller;

use App\Entity\Enseignant;
use App\Form\EnseignantType;
use App\Repository\EnseignantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil/edit/bis')]
class ProfilEditBisController extends AbstractController
{
    #[Route('/', name: 'app_profil_edit_bis_index', methods: ['GET'])]
    public function index(EnseignantRepository $enseignantRepository): Response
    {
        return $this->render('profil_edit_bis/index.html.twig', [
            'enseignants' => $enseignantRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_profil_edit_bis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EnseignantRepository $enseignantRepository): Response
    {
        $enseignant = new Enseignant();
        $form = $this->createForm(EnseignantType::class, $enseignant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $enseignantRepository->save($enseignant, true);

            return $this->redirectToRoute('app_profil_edit_bis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil_edit_bis/new.html.twig', [
            'enseignant' => $enseignant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_profil_edit_bis_show', methods: ['GET'])]
    public function show(Enseignant $enseignant): Response
    {
        return $this->render('profil_edit_bis/show.html.twig', [
            'enseignant' => $enseignant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_profil_edit_bis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Enseignant $enseignant, EnseignantRepository $enseignantRepository): Response
    {
        $form = $this->createForm(EnseignantType::class, $enseignant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $enseignantRepository->save($enseignant, true);

            return $this->redirectToRoute('app_profil_edit_bis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil_edit_bis/edit.html.twig', [
            'enseignant' => $enseignant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_profil_edit_bis_delete', methods: ['POST'])]
    public function delete(Request $request, Enseignant $enseignant, EnseignantRepository $enseignantRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$enseignant->getId(), $request->request->get('_token'))) {
            $enseignantRepository->remove($enseignant, true);
        }

        return $this->redirectToRoute('app_profil_edit_bis_index', [], Response::HTTP_SEE_OTHER);
    }
}
