<?php


namespace App\Components\Trace\TypeTrace;

class TraceTypeTexte extends AbstractTrace implements TraceInterface
{
    final public const TYPE_TRACE = 'texte';
    final public const LABEL = 'trace_texte';
    final public const BADGE = 'bg-info';
    final public const ICON = 'fas fa-link';

    final public const TEMPLATE = 'Components/Trace/type/texte.html.twig';

    public function display(): string
    {
        return 'Un message pour une trace texte';
    }
}