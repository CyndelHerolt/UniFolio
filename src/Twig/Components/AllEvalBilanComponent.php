<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent('AllEvalBilanComponent')]
final class AllEvalBilanComponent extends AllPortfolioEvalComponent
{
    public static function getComponentName(): string
    {
        return 'all_eval_bilan_component';
    }
}
