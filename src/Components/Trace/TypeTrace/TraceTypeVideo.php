<?php

namespace App\Components\Trace\TypeTrace;

class TraceTypeVideo extends AbstractTrace implements TraceInterface
{
    final public const TYPE_TRACE = 'video';
    final public const LABEL = 'trace_video';
    final public const HELP = 'Upload de vidéo : ...';
    final public const ICON = 'fas fa-link';

    final public const TEMPLATE = 'Components/Trace/type/video.html.twig';

    public function display(): string
    {
        return self::HELP;
    }

}