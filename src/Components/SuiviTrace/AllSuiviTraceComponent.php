<?php

namespace App\Components\SuiviTrace;

use App\Repository\TraceRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('all_suivi_trace')]
class AllSuiviTraceComponent
{

    public function __construct(
        private TraceRepository $traceRepository,
    )
    {
    }

    public function getAllSuiviTrace(): array
    {
        return $this->traceRepository->findAll();
    }
}