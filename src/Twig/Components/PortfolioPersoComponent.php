<?php
/*
 * Copyright (c) 2024. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */

namespace App\Twig\Components;

use App\Components\Editeur\EditeurRegistry;
use App\Controller\BaseController;
use App\Entity\PagePerso;
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
    public ?PortfolioPerso $portfolio;

    #[LiveProp(writable: true)]
    /** @var PagePerso[] */
    public ?array $pages = [];

    public function __construct(
        protected PortfolioPersoRepository $portfolioPersoRepository,
        protected PagePersoRepository      $pagePersoRepository,
        protected BlocRepository           $blocRepository,
        protected EditeurRegistry          $editeurRegistry,
    )
    {
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

        $this->portfolio = $this->portfolioPersoRepository->findPortfolioByBloc($blocId);
    }

    #[LiveAction]
    public function downBloc(#[LiveArg] ?int $blocId, #[LiveArg] ?int $pageId): void
    {
        $bloc = $this->blocRepository->find($blocId);
        $ordre = $bloc->getOrdre();
        $page = $this->pagePersoRepository->find($pageId);

        // Get the bloc with the highest order
        $highestOrderBloc = $this->blocRepository->findByPage($page, ['ordre' => 'DESC'])[0];

        // Check if the bloc is not the last in order
        if ($ordre < $highestOrderBloc->getOrdre()) {
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

        $this->portfolio = $this->portfolioPersoRepository->findPortfolioByBloc($blocId);
    }

    public function getPortfolio()
    {
        $this->pages = $this->pagePersoRepository->findBy(['portfolio' => $this->portfolio], ['ordre' => 'ASC']);
        $this->form = $this->createForm(PortfolioPersoType::class, $this->portfolio)->createView();
    }
}
