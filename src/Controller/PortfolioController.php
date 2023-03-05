<?php

namespace App\Controller;

use App\Entity\Portfolio;
use App\Form\PortfolioType;
use App\Repository\PortfolioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Service\Attribute\Required;

class PortfolioController extends AbstractController
{
    public function __construct(
        #[Required] public Security $security
    )
    {
    }

    #[Route('/portfolio', name: 'app_portfolio')]
    public function index(
        PortfolioRepository $portfolioRepository,
    ): Response
    {
        //Récupérer les portfolios de l'utilisateur connecté
        $etudiant = $this->security->getUser()->getEtudiant();
        $portfolios = $portfolioRepository->findBy(['etudiant' => $etudiant]);


        return $this->render('portfolio/index.html.twig', [
            'portfolios' => $portfolios,
        ]);
    }

    #[Route('/portfolio/new/{id}', name: 'app_portfolio_new')]
    public function new(
        Request             $request,
        PortfolioRepository $portfolioRepository,
        Security            $security
    ): Response
    {
        $user = $security->getUser()->getEtudiant();
        $portfolio = new Portfolio();

        $form = $this->createForm(PortfolioType::class, $portfolio, ['user' => $user]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pages = $form->get('pages')->getData();

            if ($pages->isEmpty()) {
                $this->addFlash('danger', 'Vous devez sélectionner au moins une page');
            } else {
                $portfolio->setEtudiant($user);

                foreach ($pages as $page) {
                    $portfolio->addPage($page);
                    $page->addPortfolio($portfolio);
                }

                $portfolioRepository->save($portfolio, true);

                $this->addFlash('success', 'Le Portfolio a été créé avec succès');
                return $this->redirectToRoute('app_portfolio');
            }
        }

        return $this->render('portfolio/new.html.twig', [
            'form' => $form->createView(),
            'portfolio' => $portfolio,
        ]);
    }

    #[Route('/portfolio/edit/{id}', name: 'app_portfolio_edit')]
    public function edit(
        Request             $request,
        PortfolioRepository $portfolioRepository,
        Security            $security,
        int                 $id
    ): Response
    {
        $user = $security->getUser()->getEtudiant();
        $portfolio = $portfolioRepository->findOneBy(['id' => $id]);
        $existingPages = $portfolio->getPages(); // Récupérer les pages déjà associées au portfolio
        $pagesToRemove = new ArrayCollection($existingPages->toArray()); // Créer une collection avec les pages déjà associées au portfolio

        $form = $this->createForm(PortfolioType::class, $portfolio, ['user' => $user]);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pages = $form->get('pages')->getData();

            if ($pages->isEmpty()) {
                $this->addFlash('danger', 'Vous devez sélectionner au moins une page');
            } else {

                //Pour chaque page déjà associée au portfolio
                foreach ($pagesToRemove as $pageToRemove) {
                    //Si la page n'est plus sélectionnée
                    if (!$pages->contains($pageToRemove)) {
                        //Retirer la page du portfolio
                        $portfolio->removePage($pageToRemove);
                        $pageToRemove->removePortfolio($portfolio);
                    }
                }

                //Pour chaque page sélectionnée
                foreach ($pages as $page) {
                    //Ajouter la page au portfolio
                    $portfolio->addPage($page);
                    $page->addPortfolio($portfolio);
                }

                $portfolioRepository->save($portfolio, true);

                $this->addFlash('success', 'Le Portfolio a été modifié avec succès');
                return $this->redirectToRoute('app_portfolio');
            }
        }


        return $this->render('portfolio/edit.html.twig', [
            'form' => $form->createView(),
            'portfolio' => $portfolio,
        ]);
    }
}
