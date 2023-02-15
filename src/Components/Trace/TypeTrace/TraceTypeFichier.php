<?php

namespace App\Components\Trace\TypeTrace;

class TraceTypeFichier extends AbstractTrace implements TraceInterface
{
    final public const TYPE_TRACE = 'fichier';
    final public const LABEL = 'trace_fichier';
    final public const BADGE = 'bg-info';
    final public const ICON = 'fas fa-link';
    final public const TEMPLATE = 'Components/Trace/type/fichier.html.twig';
    public function display(): string
    {
        return 'Un message pour une trace fichier';
    }
}