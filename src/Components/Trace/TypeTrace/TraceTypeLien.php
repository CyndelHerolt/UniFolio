<?php

namespace App\Components\Trace\TypeTrace;

class TraceTypeLien extends AbstractTrace implements TraceInterface
{

    final public const TYPE_TRACE = 'lien';
    final public const LABEL = 'trace_lien';
    final public const BADGE = 'bg-info';
    final public const ICON = 'fas fa-link';

    final public const TEMPLATE = 'Components/Trace/type/lien.html.twig';

    public function display(): string
    {
        return 'Un message pour une trace lien';
    }
}
