<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Components\Trace;

use App\Entity\Trace;
use App\Repository\TraceRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('trace_card')]
class TraceCardComponent
{
    use DefaultActionTrait;

    #[LiveProp]
    public int $id;

    public function __construct(
        private TraceRepository $traceRepository,
    ) {
    }

    public function getTrace(): ?Trace
    {
        return $this->traceRepository->find($this->id);
    }
}
