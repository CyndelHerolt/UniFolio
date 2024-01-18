<?php
/*
 * Copyright (c) 2024. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */

namespace App\Controller;

use App\Classes\DataUserSession;
use App\Components\Editeur\EditeurRegistry;
use App\Entity\Bloc;
use App\Entity\PagePerso;
use App\Entity\PortfolioPerso;
use App\Form\PortfolioPersoType;
use App\Repository\BlocRepository;
use App\Repository\PagePersoRepository;
use App\Repository\PortfolioPersoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

#[Route('/portfolio-perso')]
class PortfolioPersoController extends AbstractController
{
    public function __construct(
        #[Required] public Security      $security,
        private DataUserSession          $dataUserSession,
        private PortfolioPersoRepository $portfolioPersoRepository,
        private PagePersoRepository      $pagePersoRepository,
        private BlocRepository          $blocRepository,
        private readonly EditeurRegistry $editeurRegistry
    )
    {
    }

    #[Route('/', name: 'app_portfolio_perso')]
    public function index(): Response
    {
        if ($this->isGranted('ROLE_ETUDIANT')) {
            $data_user = $this->dataUserSession;

            //Récupérer les portfolios de l'utilisateur connecté
            $etudiant = $this->security->getUser()->getEtudiant();
            $portfolios = $this->portfolioPersoRepository->findBy(['etudiant' => $etudiant]);

            return $this->render('portfolio_perso/index.html.twig', [
                'portfolios' => $portfolios ?? null,
                'data_user' => $data_user,
            ]);
        } else {
            return $this->render('security/accessDenied.html.twig');
        }
    }

    #[Route('/new', name: 'app_portfolio_perso_new')]
    public function create(): Response
    {
        $portfolio = new PortfolioPerso();
        $portfolio->setEtudiant($this->security->getUser()->getEtudiant());
        $this->portfolioPersoRepository->save($portfolio);

        $page = new PagePerso();
        $page->setPortfolio($portfolio);
        $page->setOrdre(1);
        $this->pagePersoRepository->save($page);


        return $this->redirectToRoute('app_portfolio_perso_edit', ['id' => $portfolio->getId()]);
    }

    #[Route('/edit/{id}', name: 'app_portfolio_perso_edit')]
    public function edit(
        ?int $id,
    )
    {
        $portfolio = $this->portfolioPersoRepository->find($id);
        $pages = $this->pagePersoRepository->findBy(['portfolio' => $portfolio], ['ordre' => 'ASC']);
        $elements = $this->editeurRegistry->getElements();

        $form = $this->createForm(PortfolioPersoType::class, $portfolio);

        return $this->render('portfolio_perso/edit.html.twig', [
            'portfolio' => $portfolio,
            'pages' => $pages,
            'form' => $form->createView(),
            'elements' => $elements,
        ]);
    }

    #[Route('/new/bloc/{id}', name: 'app_portfolio_perso_new_bloc')]
    public function addBloc(
        ?int $id
    )
    {
        $page = $this->pagePersoRepository->find($id);

        $bloc = new Bloc();
        $bloc->setOrdre(1);
        $bloc->addPage($page);
        $this->blocRepository->save($bloc);

        return $this->redirectToRoute('app_portfolio_perso_edit', ['id' => $page->getPortfolio()->getId()]);
    }

    #[Route('/new/element/{element}/{id}', name: 'app_portfolio_perso_new_element')]
    public function addElement(
        ?int $id,
        ?string $element,
    ): Response
    {
        $bloc = $this->blocRepository->find($id);

        $typeElement = $this->editeurRegistry->getTypeElement($element);
//        dd($typeElement);
        $typeElement->create($element, $bloc);

        return $this->redirectToRoute('app_portfolio_perso_edit', ['id' => $bloc->getPages()->first()->getPortfolio()->getId()]);
    }

    #[Route('/new/page/{id}', name: 'app_portfolio_perso_new_page')]
    public function addPage(
        ?int $id
    )
    {
        $portfolio = $this->portfolioPersoRepository->find($id);

        $page = new PagePerso();
        $page->setPortfolio($portfolio);
        // nombre de page du portfolio + 1
        $allPages = $this->pagePersoRepository->findBy(['portfolio' => $portfolio]);
        $page->setOrdre(count($allPages) + 1);
        $this->pagePersoRepository->save($page);

        return $this->redirectToRoute('app_portfolio_perso_edit', ['id' => $portfolio->getId()]);
    }

}
