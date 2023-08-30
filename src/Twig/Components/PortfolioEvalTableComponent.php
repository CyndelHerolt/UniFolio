<?php

namespace App\Twig\Components;

use App\Controller\BaseController;
use App\Entity\Portfolio;
use App\Repository\PortfolioRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('PortfolioEvalTableComponent')]
final class PortfolioEvalTableComponent extends BaseController
{
    use DefaultActionTrait;

    #[LiveProp]
    public int $id;

    public function __construct(
        public PortfolioRepository       $portfolioRepository,
    )
    {

    }

    public function getPortfolio(): ?Portfolio
    {
        return $this->portfolioRepository->find($this->id);
    }
}