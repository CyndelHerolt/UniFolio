<?php
namespace App\Components\Trace;

use App\Entity\Trace;
use App\Repository\TraceRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('trace_card')]
class TraceCardComponent {
    // public string $title;
    // public string $content;
    public int $id;

	public function __construct(private TraceRepository $traceRepository)
    {}

	public function getTrace(): Trace {
        return $this->traceRepository->find($this->id);
    }
}
