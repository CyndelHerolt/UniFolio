<?php

namespace App\Components\Portfolio;

use App\Entity\Portfolio;
use App\Repository\PortfolioRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('portfolio_table')]
class PortfolioTableComponent
{
    public int $id;

    public function __construct(private PortfolioRepository $portfolioRepository)
    {
    }

    public function getPortfolio(): Portfolio
    {
        return $this->portfolioRepository->find($this->id);
    }
}
