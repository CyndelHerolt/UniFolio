<?php
/*
 * Copyright (c) 2024. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */

namespace App\Twig\Components;

use App\Components\Editeur\EditeurRegistry;
use App\Controller\BaseController;
use App\Entity\Bloc;
use App\Entity\PortfolioPerso;
use App\Form\PortfolioPersoType;
use App\Repository\BlocRepository;
use App\Repository\PagePersoRepository;
use App\Repository\PortfolioPersoRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('PortfolioPersoComponent')]
    class PortfolioPersoComponent extends BaseController
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public ?PortfolioPerso $portfolioPerso;

    public function __construct(
        protected PortfolioPersoRepository $portfolioPersoRepository,
        protected PagePersoRepository      $pagePersoRepository,
        protected BlocRepository           $blocRepository,
        protected EditeurRegistry          $editeurRegistry,
    )
    {
    }


    #[LiveAction]
    public function addBloc(#[LiveArg] ?int $pageId): void
    {
        $page = $this->pagePersoRepository->find($pageId);

        $bloc = new Bloc();
        $bloc->setOrdre(count($page->getBlocs()) + 1);
        $bloc->addPage($page);
        $this->blocRepository->save($bloc);

        $blocs = $this->blocRepository->findByPage($page);
        foreach ($blocs as $b) {
            $page->addBloc($b);
        }

        $this->portfolioPerso = $this->getPortfolioPerso();
    }

    #[LiveAction]
    public function deleteBloc(#[LiveArg] ?int $blocId, #[LiveArg] ?int $pageId): void
    {
        $bloc = $this->blocRepository->find($blocId);
        $page = $this->pagePersoRepository->find($pageId);
        $ordre = $bloc->getOrdre();
        $this->blocRepository->remove($bloc);
        $blocs = $this->blocRepository->findByPage($page);
        foreach ($blocs as $b) {
            if ($b->getOrdre() > $ordre) {
                $b->setOrdre($b->getOrdre() - 1);
                $this->blocRepository->save($b);
            }
        }
        $this->portfolioPerso = $this->getPortfolioPerso();
    }

    #[LiveAction]
    public function upBloc(#[LiveArg] ?int $blocId, #[LiveArg] ?int $pageId): void
    {
        $bloc = $this->blocRepository->find($blocId);
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

        $this->portfolioPerso = $this->getPortfolioPerso();
    }

    #[LiveAction]
    public function downBloc(#[LiveArg] ?int $blocId, #[LiveArg] ?int $pageId): void
    {
        $bloc = $this->blocRepository->find($blocId);
        $ordre = $bloc->getOrdre();
        $page = $this->pagePersoRepository->find($pageId);

        // Check if the bloc is not the last in order
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
        $this->portfolioPerso = $this->getPortfolioPerso();
    }

    #[LiveAction]
    public function newElement(#[LiveArg] ?int $blocId, #[LiveArg] ?string $element)
    {
        $bloc = $this->blocRepository->find($blocId);

        $typeElement = $this->editeurRegistry->getTypeElement($element);
        $typeElement->create($element, $bloc);


    }

    public function getPortfolioPerso()
    {
        $this->pages = $this->pagePersoRepository->findBy(['portfolio' => $this->portfolioPerso], ['ordre' => 'ASC']);
        $this->form = $this->createForm(PortfolioPersoType::class, $this->portfolioPerso)->createView();
        $this->elements = $this->editeurRegistry->getElements();

        return $this->portfolioPerso;
    }

}
