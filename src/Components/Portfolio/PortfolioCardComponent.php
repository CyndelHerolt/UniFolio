<?php
/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Components\Portfolio;

use App\Entity\Portfolio;
use App\Repository\PortfolioRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('portfolio_card')]
class PortfolioCardComponent
{
    public int $id;

    public function __construct(
        private PortfolioRepository $portfolioRepository)
    {}

    public function getPortfolio(): Portfolio
    {
        $portfolio = $this->portfolioRepository->find($this->id);

        // Chargement des pages associées au portfolio
        $portfolio->getPages()->toArray();


        return $portfolio;
    }
}
