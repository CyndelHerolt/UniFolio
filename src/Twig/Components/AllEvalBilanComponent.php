<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent('AllEvalBilanComponent')]
final class AllEvalBilanComponent extends AllPortfolioEvalComponent
{
    public static function getComponentName(): string {
        return 'all_eval_bilan_component';
    }
}
