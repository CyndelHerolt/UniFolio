<?php

namespace App\Components\Trace\TypeTrace;

use App\Components\Trace\Form\TraceTypeImageType;
use App\Components\Trace\Form\TraceTypeLienType;

class TraceTypeLien extends AbstractTrace implements TraceInterface
{

    final public const TAG_TYPE_TRACE = 'lien';
    final public const FORM = TraceTypeLienType::class;
    final public const HELP = 'Upload de lien - lien absolu';
    final public const ICON = 'fas fa-link';

    final public const TEMPLATE = 'Components/Trace/type/lien.html.twig';

    public function display(): string
    {
        return self::HELP;
    }

    public function save($form, $trace, $traceRepository, $traceRegistry): bool
    {
        // TODO: Implement save() method.
    }
}
