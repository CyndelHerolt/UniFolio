<?php

namespace App\Controller;

use App\Components\Editeur\EditeurRegistry;
use App\Repository\BlocRepository;
use App\Repository\ElementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/portfolio-perso')]
class ElementController extends AbstractController
{
    public function __construct(
        private BlocRepository           $blocRepository,
        private ElementRepository        $elementRepository,
        private readonly EditeurRegistry $editeurRegistry
    )
    {
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

    #[Route('/{portfolioId}/delete/element/{id}', name: 'app_portfolio_perso_delete_element')]
    public function deleteElement(
        ?int $id,
        ?int $portfolioId
    )
    {
        $element = $this->elementRepository->find($id);
        $this->elementRepository->delete($element);

        return $this->redirectToRoute('app_portfolio_perso_edit', ['id' => $portfolioId]);
    }

    #[Route('/{portfolioId}/edit/element/{id}/{col}', name: 'app_portfolio_perso_element_col')]
    public function setCol(
        ?int $portfolioId,
        ?int $id,
        ?int $col,
    ): Response
    {
        $element = $this->elementRepository->find($id);
        $element->setColonne('col-' . $col);
        $this->elementRepository->save($element);

        return $this->redirectToRoute('app_portfolio_perso_edit', ['id' => $portfolioId]);
    }

    #[Route('/{portfolioId}/edit/element/{id}/up/{blocId}', name: 'app_portfolio_perso_element_up')]
    public function upElement(
        ?int $portfolioId,
        ?int $id,
        ?int $blocId,
    ): Response
    {
        $element = $this->elementRepository->find($id);
        $ordre = $element->getOrdre();
        $bloc = $this->blocRepository->find($blocId);
        if ($ordre > 1) {
            $elements = $this->elementRepository->findBy(['bloc' => $bloc]);
            foreach ($elements as $e) {
                if ($e->getOrdre() == $ordre - 1) {
                    $e->setOrdre($e->getOrdre() + 1);
                    $this->elementRepository->save($e);
                }
            }
            $element->setOrdre($ordre - 1);
            $this->elementRepository->save($element);
        }

        return $this->redirectToRoute('app_portfolio_perso_edit', ['id' => $portfolioId]);
    }

    #[Route('/{portfolioId}/edit/element/{id}/down/{blocId}', name: 'app_portfolio_perso_element_down')]
    public function downElement(
        ?int $portfolioId,
        ?int $id,
        ?int $blocId,
    ): Response
    {
        $element = $this->elementRepository->find($id);
        $ordre = $element->getOrdre();
        $bloc = $this->blocRepository->find($blocId);
        if ($ordre != count($bloc->getElements())) {
            $elements = $this->elementRepository->findBy(['bloc' => $bloc]);
            foreach ($elements as $e) {
                if ($e->getOrdre() == $ordre + 1) {
                    $e->setOrdre($e->getOrdre() - 1);
                    $this->elementRepository->save($e);
                }
            }
            $element->setOrdre($ordre + 1);
            $this->elementRepository->save($element);
        }

        return $this->redirectToRoute('app_portfolio_perso_edit', ['id' => $portfolioId]);
    }
}