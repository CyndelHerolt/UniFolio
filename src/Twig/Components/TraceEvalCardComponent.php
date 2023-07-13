<?php

namespace App\Twig\Components;

use App\Entity\Trace;
use App\Repository\TraceRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('TraceEvalCardComponent')]

class TraceEvalCardComponent
{
    use DefaultActionTrait;

    #[LiveProp]
    public int $id;

    public function __construct(
        public TraceRepository $traceRepository,
    )
    {
    }

    public function getTrace(): ?Trace
    {
        return $this->traceRepository->find($this->id);
    }
}