<?php
/*
 * Copyright (c) 2024. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */

namespace App\Twig\Components;

use A\B;
use App\Components\Editeur\EditeurRegistry;
use App\Entity\Bloc;
use App\Entity\PagePerso;
use App\Entity\PortfolioPerso;
use App\Repository\BlocRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsLiveComponent('BlocComponent')]
final class BlocComponent
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public ?int $id;

    #[LiveProp(writable: true)]
    public ?PagePerso $page;

    #[LiveProp(writable: true)]
    public PortfolioPerso $portfolio;


    public function __construct(
        private BlocRepository  $blocRepository,
        private EditeurRegistry $editeurRegistry
    )
    {
    }

    #[PostMount]
    public function init()
    {
        dump($this->id);
        $this->elements = $this->editeurRegistry->getElements();
        $this->bloc = $this->blocRepository->find($this->id);
    }
}
