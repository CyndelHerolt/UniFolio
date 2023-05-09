<?php

namespace App\Controller;

use App\Entity\Enseignant;
use App\Repository\EnseignantDepartementRepository;
use App\Repository\EnseignantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route('/security', name: 'app_security')]
    public function index(): Response
    {
        return $this->render('security/choix-departement.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    #[Route(path: '/choix-departement', name: 'app_choix_departement')]
    public function choixDepartement(EntityManagerInterface          $entityManager,
                                     RequestStack                    $session,
                                     Request                         $request,
                                     EnseignantDepartementRepository $enseignantDepartementRepository,
                                     EnseignantRepository            $enseignantRepository,
    )
    {
        // TODO: pourquoi la méthode n'est pas executée ?
        $user = $this->getUser();
        $enseignant = $enseignantRepository->findOneBy(['username' => $user->getUsername()]);
        if ($enseignant) {
            return $this->redirectToRoute('app_login', ['message' => 'Vous devez être intervenant ou enseignant pour accéder à cette page.']);
        }

        $departements = $enseignantDepartementRepository->findByEnseignant($enseignant);
        $update = null;
        if ('POST' === $request->getMethod()) {
            foreach ($departements as $departement) {
                if (null !== $departement->getDepartement()) {
                    if ($departement->getDepartement()->getId() !== (int)$request->request->get('departement')) {
                        $departement->setDefaut(false);
                    } elseif ($departement->getDepartement()->getId() === (int)$request->request->get('departement')) {
                        $departement->setDefaut(true);
                        $update = $departement;
                    }
                }
            }

        }
        return $this->render('security/choix-departement.html.twig',
            ['departements' => $departements, 'user' => $user]);
    }
}