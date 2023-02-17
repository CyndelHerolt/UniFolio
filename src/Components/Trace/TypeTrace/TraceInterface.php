<?php


namespace App\Components\Trace\TypeTrace;

interface TraceInterface
{
    public function display(): string;
    public function save($form, $trace, $traceRepository, $traceRegistry): bool;
}