<?php

namespace App\Controller;

use App\Entity\Cv;
use App\Form\CvType;
use App\Repository\CvRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

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
        $etudiant = $this->security->getUser()->getEtudiant();

        $cvs = $etudiant->getCvs();

        foreach ($cvs as $cv) {
            dump($cv->getIntitule());
        }

        return $this->render('cv/index.html.twig', [
            'controller_name' => 'CvController',
        ]);
    }

    #[Route('/cv/new/{id}', name: 'app_cv_new')]
    public function new(
        Request      $request,
        CvRepository $cvRepository,
    ): Response
    {
        $user = $this->security->getUser()->getEtudiant();
        $cv = new Cv();

        $form = $this->createForm(CvType::class, $cv, ['user' => $user]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $cv->setEtudiant($user);
            $cv->setDateCreation(new \DateTime());
            $cv->setDateModification(new \DateTime());
            $cvRepository->save($cv, true);

            return $this->redirectToRoute('app_cv');
        }

        return $this->render('cv/new.html.twig', [
            'form' => $form->createView(),
            ]);
    }
}
