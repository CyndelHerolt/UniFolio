<?php
/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Twig\Components;

use App\Controller\BaseController;
use App\Entity\Commentaire;
use App\Entity\Portfolio;
use App\Form\CommentaireType;
use App\Repository\PortfolioRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('PortfolioEvalCardComponent')]
final class PortfolioEvalCardComponent extends BaseController
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
