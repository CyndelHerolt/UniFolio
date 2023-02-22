<?php

namespace App\Components\Trace\TypeTrace;

use App\Components\Trace\Form\TraceTypeVideoType;

class TraceTypeVideo extends AbstractTrace implements TraceInterface
{
    final public const TAG_TYPE_TRACE = 'video';
    final public const FORM = TraceTypeVideoType::class;
    final public const HELP = 'Upload de vidéo : lien Youtube';
    final public const ICON = 'fa-brands fa-3x fa-youtube';
    final public const TEMPLATE = 'Components/Trace/type/video.html.twig';

    public function display(): string
    {
        return self::TAG_TYPE_TRACE;
    }

    public function save($form, $trace, $traceRepository, $traceRegistry): array
    {
        $contenu = $form['contenu']->getData();
        $youtubeId = null;

        // Vérifier si le contenu est un lien YouTube
        if (preg_match('/^(https?:\/\/)?(www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $contenu, $matches)) {
            $youtubeId = $matches[3];
        } elseif (preg_match('/^(https?:\/\/)?(www\.)?youtu\.be\/([a-zA-Z0-9_-]+)/', $contenu, $matches)) {
            $youtubeId = $matches[3];
        }
        if ($youtubeId) {
            // Construire le lien embed à partir de l'ID
            $contenu = 'https://www.youtube.com/embed/' . $youtubeId;
            // Sauvegarder le contenu dans la base de données
            $trace->setContenu($contenu);
            return array('success' => true);
        } else {
            $error = 'Le lien n\'est pas un lien YouTube valide';
            return array('success' => false, 'error' => $error);
        }
    }
}