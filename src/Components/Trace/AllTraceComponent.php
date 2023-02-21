<?php
namespace App\Components\Trace;

use App\Entity\Trace;
use App\Repository\TraceRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('all_trace')]
class AllTraceComponent {

	public function __construct(private TraceRepository $traceRepository)
    {}

	public function getAllTrace(): array
    {
        return $this->traceRepository->findAll();
    }
}