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
    public ?int $id;

    private ?Portfolio $portfolioCache = null;
    private ?int $portfolioCacheId = null;

    public function __construct(
        public PortfolioRepository $portfolioRepository,
    ) {
    }

    public function getPortfolio(): ?Portfolio
    {
        if ($this->id === null) {
            return null;
        }

        if ($this->portfolioCacheId === $this->id && $this->portfolioCache !== null) {
            return $this->portfolioCache;
        }

        $this->portfolioCache = $this->portfolioRepository->findOneForEvaluationCard($this->id);
        $this->portfolioCacheId = $this->id;

        return $this->portfolioCache;
    }
}
