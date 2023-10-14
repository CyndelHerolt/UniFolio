<?php
/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
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