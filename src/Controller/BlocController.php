<?php
/*
 * Copyright (c) 2024. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Controller;

use App\Entity\Bloc;
use App\Repository\BlocRepository;
use App\Repository\PagePersoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[Route('/portfolio-perso')]
class BlocController extends AbstractController
{
    public function __construct(
        private BlocRepository $blocRepository,
        private PagePersoRepository $pagePersoRepository
    )
    {
    }

    #[Route('/new/bloc/{id}', name: 'app_portfolio_perso_new_bloc')]
    public function addBloc(
        ?int $id
    )
    {
        $page = $this->pagePersoRepository->find($id);

        $bloc = new Bloc();
        $bloc->setOrdre(count($page->getBlocs()) + 1);
        $bloc->addPage($page);
        $this->blocRepository->save($bloc);

        return $this->redirectToRoute('app_portfolio_perso_edit', ['id' => $page->getPortfolio()->getId()]);
    }

    #[Route('/{portfolioId}/edit/bloc/{id}/up/{pageId}', name: 'app_portfolio_perso_bloc_up')]
    public function upBloc(
        ?int $portfolioId,
        ?int $id,
        ?int $pageId,
    ): Response
    {
        $bloc = $this->blocRepository->find($id);
        $ordre = $bloc->getOrdre();
        $page = $this->pagePersoRepository->find($pageId);
        if ($ordre > 1) {
            $blocs = $this->blocRepository->findByPage($page);
            foreach ($blocs as $b) {
                if ($b->getOrdre() == $ordre - 1) {
                    $b->setOrdre($b->getOrdre() + 1);
                    $this->blocRepository->save($b);
                }
            }
            $bloc->setOrdre($ordre - 1);
            $this->blocRepository->save($bloc);
        }

        return $this->redirectToRoute('app_portfolio_perso_edit', ['id' => $portfolioId]);
    }

    #[Route('/{portfolioId}/edit/bloc/{id}/down/{pageId}', name: 'app_portfolio_perso_bloc_down')]
    public function downBloc(
        ?int $portfolioId,
        ?int $id,
        ?int $pageId,
    ): Response
    {
        $bloc = $this->blocRepository->find($id);
        $ordre = $bloc->getOrdre();
            $page = $this->pagePersoRepository->find($pageId);
        if ($ordre != count($page->getBlocs())) {
            $blocs = $this->blocRepository->findByPage($page);
            foreach ($blocs as $b) {
                if ($b->getOrdre() == $ordre + 1) {
                    $b->setOrdre($b->getOrdre() - 1);
                    $this->blocRepository->save($b);
                }
            }
            $bloc->setOrdre($ordre + 1);
            $this->blocRepository->save($bloc);
        }

        return $this->redirectToRoute('app_portfolio_perso_edit', ['id' => $portfolioId]);
    }

    #[Route('/{portfolioId}/edit/bloc/{id}/justify/{justify}', name: 'app_portfolio_perso_bloc_justify')]
    public function setJustify(
        ?int    $portfolioId,
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
        ?int    $portfolioId,
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
        ?int    $portfolioId,
        ?int    $id,
        ?string $fontSize
    ): Response
    {
        $bloc = $this->blocRepository->find($id);
        $bloc->setFontSize($fontSize);
        $this->blocRepository->save($bloc);

        return $this->redirectToRoute('app_portfolio_perso_edit', ['id' => $portfolioId]);
    }

    #[Route('/{portfolioId}/delete/bloc/{id}', name: 'app_portfolio_perso_delete_bloc')]
    public function deleteBloc(
        ?int $id,
        ?int $portfolioId
    )
    {
        $bloc = $this->blocRepository->find($id);
        $this->blocRepository->remove($bloc);

        return $this->redirectToRoute('app_portfolio_perso_edit', ['id' => $portfolioId]);
    }
}
