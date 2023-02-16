<?php

namespace App\Components\Trace\TypeTrace;

class TraceTypeLien extends AbstractTrace implements TraceInterface
{

    final public const TAG_TYPE_TRACE = 'lien';
    final public const LABEL = 'trace_lien';
    final public const HELP = 'Upload de lien - lien absolu';
    final public const ICON = 'fas fa-link';

    final public const TEMPLATE = 'Components/Trace/type/lien.html.twig';

    public function display(): string
    {
        return self::HELP;
    }
}
