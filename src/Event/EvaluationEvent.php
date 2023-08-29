<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class EvaluationEvent extends Event
{
    final public const EVALUATED = 'evaluation.effectuee';

    public function __construct(protected $evaluation)
    {
    }

    public function getEvaluation(): mixed
    {
        return $this->evaluation;
    }
}