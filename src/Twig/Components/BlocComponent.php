<?php

namespace App\Twig\Components;

use App\Entity\Bloc;
use App\Entity\PagePerso;
use App\Entity\PortfolioPerso;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('BlocComponent')]
class BlocComponent
{
    use DefaultActionTrait;

    #[LiveProp(writable: true, updateFromParent: true)]
    public ?Bloc $bloc;

    #[LiveProp(writable: true)]
    public ?PagePerso $page;

    #[LiveProp(writable: true, updateFromParent: true)]
    public ?PortfolioPerso $portfolioPersob;


}