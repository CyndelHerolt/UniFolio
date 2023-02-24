<?php

namespace App\Controller;

use App\Entity\Enseignant;
use App\Entity\Etudiant;
use App\Form\EnseignantType;
use App\Form\EtudiantPartialType;
use App\Form\EtudiantType;
use App\Repository\EnseignantRepository;
use App\Repository\EtudiantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/profil/edit')]
class ProfilEditController extends AbstractController
{

    #[Route('/', name: 'app_profil_edit_index', methods: ['GET'])]
    public function index(
        EtudiantRepository   $etudiantRepository,
        EnseignantRepository $enseignantRepository,
    ): Response
    {
        return $this->render('profil_edit/index.html.twig', [
            'etudiants' => $etudiantRepository->findAll(),
            'enseignants' => $enseignantRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_profil_edit_new', methods: ['GET', 'POST'])]
    public function new(
        Request              $request,
        EtudiantRepository   $etudiantRepository,
        EnseignantRepository $enseignantRepository,
    ): Response
    {

        $etudiant = new Etudiant();
        $enseignant = new Enseignant();

        if ($this->isGranted('ROLE_ETUDIANT')) {
            $form = $this->createForm(EtudiantType::class, $etudiant);
        } elseif ($this->isGranted('ROLE_ENSEIGNANT')) {
            $form = $this->createForm(EnseignantType::class, $enseignant);
        }
//        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($this->isGranted('ROLE_ETUDIANT')) {
                $etudiantRepository->save($etudiant, true);
            } //            $etudiantRepository->save($etudiant, true);
            elseif ($this->isGranted('ROLE_ENSEIGNANT')) {
                $enseignantRepository->save($enseignant, true);
            }

            return $this->redirectToRoute('app_profil_edit_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('profil_edit/new.html.twig', [
            'enseignant' => $enseignant,
            'etudiant' => $etudiant,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_profil_edit_show', methods: ['GET'])]
    public function show(
        Etudiant   $etudiant,
        Enseignant $enseignant,
    ): Response
    {
        return $this->render('profil_edit/show.html.twig', [
            'etudiant' => $etudiant,
            'enseignant' => $enseignant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_profil_edit_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request              $request,
        ?Etudiant            $etudiant,
        ?Enseignant          $enseignant,
        EtudiantRepository   $etudiantRepository,
        EnseignantRepository $enseignantRepository,
    ): Response
    {
        if ($this->isGranted('ROLE_ETUDIANT')) {
            $form = $this->createForm(EtudiantPartialType::class, $etudiant);
        } elseif ($this->isGranted('ROLE_ENSEIGNANT')) {
            $form = $this->createForm(EnseignantType::class, $enseignant);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isGranted('ROLE_ETUDIANT')) {
                $etudiantRepository->save($etudiant, true);
            } elseif ($this->isGranted('ROLE_ENSEIGNANT')) {
                $enseignantRepository->save($enseignant, true);
            }

            return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profil_edit/edit.html.twig', [
            'enseignant' => $enseignant,
            'etudiant' => $etudiant,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_profil_edit_delete', methods: ['POST'])]
    public function delete(Request $request, Etudiant $etudiant, EtudiantRepository $etudiantRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $etudiant->getId(), $request->request->get('_token'))) {
            $etudiantRepository->remove($etudiant, true);
        }

        return $this->redirectToRoute('app_profil_edit_index', [], Response::HTTP_SEE_OTHER);
    }
}
