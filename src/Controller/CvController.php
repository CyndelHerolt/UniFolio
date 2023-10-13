<?php

namespace App\Controller;

use App\Entity\Cv;
use App\Form\CvType;
use App\Form\ExperienceType;
use App\Repository\CvRepository;
use App\Repository\ExperienceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

#[Route('/etudiant')]
class CvController extends AbstractController
{
    public function __construct(
        #[Required] public Security $security,
    )
    {
    }

    #[Route('/cv', name: 'app_cv')]
    public function index(): Response
    {

        $this->denyAccessUnlessGranted(
            'ROLE_ETUDIANT'
        );

        $etudiant = $this->security->getUser()->getEtudiant();

        $cvs = $etudiant->getCvs();

        return $this->render('cv/index.html.twig', [
            'cvs' => $cvs,
        ]);
    }

    #[Route('/cv/new/{id}', name: 'app_cv_new')]
    public function new(
        Request      $request,
        CvRepository $cvRepository,
    ): Response
    {

        $this->denyAccessUnlessGranted(
            'ROLE_ETUDIANT'
        );

        $user = $this->security->getUser()->getEtudiant();
        $cv = new Cv();

        $form = $this->createForm(CvType::class, $cv, ['user' => $user]);
        $experienceForm = $this->createForm(ExperienceType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //------------------------------AJOUT EXPERIENCE--------------------------------
            foreach ($cv->getExperience() as $experience) {
                $cv->addExperience($experience);
                $experience->addCv($cv);
            }
            //-------------------------------------------------------------------------------
            //------------------------------AJOUT FORMATION--------------------------------
            foreach ($cv->getFormation() as $formation) {
                $cv->addFormation($formation);
                $formation->addCv($cv);
            }
            //-------------------------------------------------------------------------------

//            dd($request);


            $cv->setEtudiant($user);
            $cv->setDateCreation(new \DateTimeImmutable());
            $cv->setDateModification(new \DateTimeImmutable());
            $cvRepository->save($cv, true);

            return $this->redirectToRoute('app_cv');
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Le formulaire n\'est pas valide');
        }

        return $this->render('cv/formCv.html.twig', [
            'form' => $form->createView(),
            'experience' => $experienceForm->createView(),
        ]);
    }

    #[Route('/cv/edit/{id}', name: 'app_cv_edit')]
    public function edit(
        Request                $request,
        CvRepository           $cvRepository,
        ExperienceRepository   $experienceRepository,
        Cv                     $cv,
        int                    $id,
        EntityManagerInterface $entityManager
    ): Response
    {

        $this->denyAccessUnlessGranted(
            'ROLE_ETUDIANT'
        );

        $user = $this->security->getUser()->getEtudiant();

        $cv = $cvRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(CvType::class, $cv, ['user' => $user]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cv = $form->getData(); // Récupère l'objet CV mis à jour par le formulaire
            $cv->setDateModification(new \DateTimeImmutable());
            // On set les activités des expériences du CV avec les nouvelles données saisies dans le formulaire
            foreach ($cv->getExperience() as $experience) {
                $experience->setActivite($experience->getActivite());
            }

//            dd($request);

            $cvRepository->save($cv, true);

            return $this->redirectToRoute('app_cv');
        }

        return $this->render('cv/formCv.html.twig', [
            'form' => $form->createView(),
            'cv' => $cv,
        ]);
    }

    #[Route('/cv/delete/{id}', name: 'app_cv_delete')]
    public function delete(
        CvRepository $cvRepository,
        int          $id,
    ): Response
    {

        $this->denyAccessUnlessGranted(
            'ROLE_ETUDIANT'
        );

        $cv = $cvRepository->findOneBy(['id' => $id]);

        $cvRepository->remove($cv, true);
        $this->addFlash('success', 'Le CV a été supprimé avec succès.');

        return $this->redirectToRoute('app_cv', [
            'cv' => $cv,
        ]);
    }

    #[Route('/cv/show/{id}', name: 'app_cv_show')]
    public function show(
        CvRepository $cvRepository,
        int          $id,
    ): Response
    {

        $this->denyAccessUnlessGranted(
            'ROLE_ETUDIANT'
        );

        $cv = $cvRepository->findOneBy(['id' => $id]);

        return $this->render('cv/show.html.twig', [
            'cv' => $cv,
        ]);
    }
}
