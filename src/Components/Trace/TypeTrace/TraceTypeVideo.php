<?php

namespace App\Components\Trace\TypeTrace;

use App\Components\Trace\Form\TraceTypeVideoType;
use App\Repository\TraceRepository;

class TraceTypeVideo extends AbstractTrace implements TraceInterface
{
    final public const TAG_TYPE_TRACE = 'video';
    final public const FORM = TraceTypeVideoType::class;
    final public const HELP = 'Upload de vidéo : lien Youtube';
    final public const ICON = 'fa-brands fa-3x fa-youtube';
    final public const TEMPLATE = 'Components/Trace/type/video.html.twig';

    public function __construct(protected TraceRepository $traceRepository)
    {
        $this->type_trace = 'TraceTypeVideo';
    }

    public function display(): string
    {
        return self::TAG_TYPE_TRACE;
    }

    public function getTypeTrace(): ?string
    {
        return $this->type_trace;
    }

    public function save($form, $trace, $traceRepository, $traceRegistry): array
    {
        $contenus = $form['contenu']->getData();

        $youtubeId = null;
        foreach ($contenus as $contenu) {
            if (preg_match('/^(https?:\/\/)?(www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $contenu, $matches) || preg_match('/^(https?:\/\/)?(www\.)?youtu\.be\/([a-zA-Z0-9_-]+)/', $contenu, $matches)) {
//                dd($matches);
                $youtubeId = $matches[3];
            }
                if ($youtubeId) {
                    // Construire le lien embed à partir de l'ID
                    $Embedcontenu = 'https://www.youtube.com/embed/' . $youtubeId;
                    // Ajouter le lien embed au tableau des contenus
                    $contenus[] = $Embedcontenu;
                    $contenus = array_diff($contenus, array($contenu));
                }
            if (!$youtubeId && !preg_match('/^(https?:\/\/)?(www\.)?youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $contenu)) {
                $error = 'Le lien n\'est pas un lien YouTube valide';
                return array('success' => false, 'error' => $error);
            }
        }
        // Ajouter les contenus au tableau des contenus
//        dd($contenus);
        $trace->setContenu($contenus);
        return array('success' => true);
    }
}