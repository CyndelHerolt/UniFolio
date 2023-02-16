<?php


namespace App\Components\Trace;

use App\Components\Trace\TypeTrace\AbstractTrace;
use App\Entity\Trace;

class TraceRegistry
{
    public const TAG_TYPE_TRACE = 'type_trace';

    private array $typeTraces = [];

    public function registerTypeTrace(string $name, AbstractTrace $abstractTrace): void
    {
        $this->typeTraces[$name] = $abstractTrace;
    }

    public function getTypeTrace(string $name): AbstractTrace
    {
        return $this->typeTraces[$name];
    }

    public function getTypeTraces(): array
    {
        return $this->typeTraces;
    }

    public function getChoiceTypeTraceName(): array
    {
        $tab = [];
        foreach ($this->typeTraces as $name) {
            $tab[$name::TAG_TYPE_TRACE] = $name::class;
        }
        return $tab;
    }
}

