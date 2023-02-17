<?php


namespace App\Components\Trace\TypeTrace;

use App\Components\Trace\Form\TraceTypePdfType;

class TraceTypePdf extends AbstractTrace implements TraceInterface
{
    final public const TAG_TYPE_TRACE = 'pdf';
    final public const FORM = TraceTypePdfType::class;
    final public const HELP = 'Upload de pdf : Taille maximale XMo';
    final public const ICON = 'fas fa-link';

    final public const TEMPLATE = 'Components/Trace/type/pdf.html.twig';

    public function display(): string
    {
        return self::HELP;
    }

    public function save($form, $trace, $traceRepository, $traceRegistry): bool
    {
        // TODO: Implement save() method.
    }
}