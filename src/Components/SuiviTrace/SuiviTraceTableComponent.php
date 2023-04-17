<?php

namespace App\Components\SuiviTrace;

use App\Entity\Trace;
use App\Repository\TraceRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('suivi_trace_table')]
class SuiviTraceTableComponent
{
public int $id;

    public function __construct(private TraceRepository $traceRepository)
    {
    }

    public function getTrace(): Trace
    {
        return $this->traceRepository->find($this->id);
    }
}