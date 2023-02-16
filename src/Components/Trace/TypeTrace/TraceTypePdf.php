<?php


namespace App\Components\Trace\TypeTrace;

class TraceTypePdf extends AbstractTrace implements TraceInterface
{
    final public const TAG_TYPE_TRACE = 'pdf';
    final public const LABEL = 'trace_pdf';
    final public const HELP = 'Upload de pdf : Taille maximale XMo';
    final public const ICON = 'fas fa-link';

    final public const TEMPLATE = 'Components/Trace/type/pdf.html.twig';

    public function display(): string
    {
        return self::HELP;
    }
}