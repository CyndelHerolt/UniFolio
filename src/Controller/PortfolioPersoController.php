<?php
/*
 * Copyright (c) 2024. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */

namespace App\Controller;

use App\Classes\DataUserSession;
use App\Components\Editeur\EditeurRegistry;
use App\Components\Editeur\Form\ElementType;
use App\Entity\Bloc;
use App\Entity\PagePerso;
use App\Entity\PortfolioPerso;
use App\Form\PortfolioPersoType;
use App\Repository\BlocRepository;
use App\Repository\ElementRepository;
use App\Repository\PagePersoRepository;
use App\Repository\PortfolioPersoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
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
        private BlocRepository           $blocRepository,
        private ElementRepository        $elementRepository,
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

    #[Route('/{id}/new/element/{element}/{blocId}', name: 'app_portfolio_perso_new_element')]
    public function addElement(
        ?int    $id,
        ?int    $blocId,
        ?string $element,
    ): Response
    {
        $bloc = $this->blocRepository->find($blocId);

        $typeElement = $this->editeurRegistry->getTypeElement($element);
        $typeElement->create($element, $bloc);

        return $this->redirectToRoute('app_portfolio_perso_edit', ['id' => $id]);
    }

    #[Route('/{portfolioId}/edit/element/{id}/{col}', name: 'app_portfolio_perso_element_col')]
    public function setCol(
        ?int $portfolioId,
        ?int    $id,
        ?int $col,
    ): Response
    {
        $element = $this->elementRepository->find($id);
        $element->setColonne('col-'.$col);
        $this->elementRepository->save($element);

        return $this->redirectToRoute('app_portfolio_perso_edit', ['id' => $portfolioId]);
    }

    #[Route('/{portfolioId}/edit/bloc/{id}/justify/{justify}', name: 'app_portfolio_perso_bloc_justify')]
    public function setJustify(
        ?int $portfolioId,
        ?int    $id,
        ?string $justify
    ): Response
    {
        $bloc = $this->blocRepository->find($id);
        $bloc->setJustify($justify);
        $this->blocRepository->save($bloc);

        return $this->redirectToRoute('app_portfolio_perso_edit', ['id' => $portfolioId]);
    }

    #[Route('/{portfolioId}/edit/bloc/{id}/direction/{direction}', name: 'app_portfolio_perso_bloc_direction')]
    public function setDirection(
        ?int $portfolioId,
        ?int    $id,
        ?string $direction
    ): Response
    {
        $bloc = $this->blocRepository->find($id);
        $bloc->setDirection($direction);
        $this->blocRepository->save($bloc);

        return $this->redirectToRoute('app_portfolio_perso_edit', ['id' => $portfolioId]);
    }

    #[Route('/{portfolioId}/edit/bloc/{id}/fontSize/{fontSize}', name: 'app_portfolio_perso_bloc_font_size')]
    public function setFontSize(
        ?int $portfolioId,
        ?int    $id,
        ?string $fontSize
    ): Response
    {
        $bloc = $this->blocRepository->find($id);
        $bloc->setFontSize($fontSize);
        $this->blocRepository->save($bloc);

        return $this->redirectToRoute('app_portfolio_perso_edit', ['id' => $portfolioId]);
    }

    #[Route('/{portfolioId}/delete/element/{id}', name: 'app_portfolio_perso_delete_element')]
    public function delete(
        ?int $id,
        ?int $portfolioId
    )
    {
        $element = $this->elementRepository->find($id);
        $this->elementRepository->delete($element);

        return $this->redirectToRoute('app_portfolio_perso_edit', ['id' => $portfolioId]);
    }


    #[Route('/new/page/{id}', name: 'app_portfolio_perso_new_page')]
    public function addPage(
        ?int $id
    )
    {
        $portfolio = $this->portfolioPersoRepository->find($id);

        $page = new PagePerso();
        $page->setPortfolio($portfolio);
        $allPages = $this->pagePersoRepository->findBy(['portfolio' => $portfolio]);
        $page->setOrdre(count($allPages) + 1);
        $this->pagePersoRepository->save($page);

        return $this->redirectToRoute('app_portfolio_perso_edit', ['id' => $portfolio->getId()]);
    }

    #[Route('/{portfolioId}/save/{id}', name: 'app_portfolio_perso_save_element')]
    public function saveElement(
        ?int    $portfolioId,
        ?int $id,
        Request $request,
    ): Response
    {
        $element = $this->elementRepository->find($id);

        $typeElement = $this->editeurRegistry->getTypeElement($element->getType());
//        dd($request->request->all());
        $typeElement->sauvegarde($typeElement, $request, $element);

        return $this->redirectToRoute('app_portfolio_perso_edit', ['id' => $portfolioId]);
    }
}
