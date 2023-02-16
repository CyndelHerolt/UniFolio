<?php

namespace App\Components\Trace\TypeTrace;

use App\Components\Trace\Form\TraceTypeImageType;

class TraceTypeVideo extends AbstractTrace implements TraceInterface
{
    final public const TAG_TYPE_TRACE = 'video';
    final public const FORM = TraceTypeImageType::class;
    final public const FORM_TEMPLATE = 'trace_depot_image.html.twig';
    final public const HELP = 'Upload de vidéo : lien Youtube';
    final public const ICON = 'fas fa-link';

    final public const TEMPLATE = 'Components/Trace/type/video.html.twig';

    public function display(): string
    {
        return self::HELP;
    }

}