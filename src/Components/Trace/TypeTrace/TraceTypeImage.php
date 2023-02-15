<?php

namespace App\Components\Trace\TypeTrace;

class TraceTypeImage extends AbstractTrace implements TraceInterface
{
    final public const TYPE_TRACE = 'image';
    final public const LABEL = 'trace_image';
    final public const HELP = 'Upload d\'image - format accepté : jpg, jpeg, png, gif';
    final public const ICON = 'fas fa-link';
    final public const TEMPLATE = 'Components/Trace/type/image.html.twig';
    public function display(): string
    {
        return self::HELP;
    }
}