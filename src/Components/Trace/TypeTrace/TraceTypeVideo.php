<?php
/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
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
    final public const ID = '4';

    public function __construct(protected TraceRepository $traceRepository)
    {
        $this->type_trace = 'TraceTypeVideo';
    }

    public function display(): string
    {
        return self::TAG_TYPE_TRACE;
    }

    public function getId(): ?string
    {
        return self::ID;
    }

    public function getTypeTrace(): ?string
    {
        return $this->type_trace;
    }

    public function save($form, $trace, $traceRepository, $traceRegistry, $existingContenu): array
    {
        $videos = $form['contenu']->getData();

        $contenu = [];
        if ($existingContenu != null) {
            foreach ($existingContenu as $content) {
                $contenu[] = $content;
            }
        }

        if ($videos) {
            foreach ($videos as $key => $video) {
                $youtubeId = null;
                if (preg_match('/^(https?:\/\/)?(www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $video, $matches)
                    || preg_match('/^(https?:\/\/)?(www\.)?youtu\.be\/([a-zA-Z0-9_-]+)/', $video, $matches)) {

                    $youtubeId = $matches[3];
                }

                if ($youtubeId) {
                    // Construire le lien embed à partir de l'ID
                    $Embedcontenu = 'https://www.youtube.com/embed/' . $youtubeId;

                    // Remplacer le lien original par le lien embed dans le tableau des video
                    $contenu[$key] = $Embedcontenu;

                }

                // Vérification des liens non youtube
                if (!$youtubeId && !preg_match('/^(https?:\/\/)?(www\.)?youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $video)) {
                    $error = 'Le lien n\'est pas un lien YouTube valide';
                    return array('success' => false, 'error' => $error);
                }
            }
        }

//        dd($contenu);
        $trace->setContenu($contenu);
        return array('success' => true);
    }
}