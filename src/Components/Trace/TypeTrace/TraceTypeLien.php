<?php

namespace App\Components\Trace\TypeTrace;

use App\Components\Trace\Form\TraceTypeImageType;
use App\Components\Trace\Form\TraceTypeLienType;

class TraceTypeLien extends AbstractTrace implements TraceInterface
{

    final public const TAG_TYPE_TRACE = 'lien';
    final public const FORM = TraceTypeLienType::class;
    final public const HELP = 'Upload de lien - lien absolu';
    final public const ICON = 'fa-solid fa-link';

    final public const TEMPLATE = 'Components/Trace/type/lien.html.twig';

    public function display(): string
    {
        return self::HELP;
    }

    public function save($form, $trace, $traceRepository, $traceRegistry): array
    {
        $contenu = $form['contenu']->getData();

        // Vérifier si le contenu est un lien valide
        if (filter_var($contenu, FILTER_VALIDATE_URL)) {
            // Sauvegarder le contenu dans la base de données
            $trace->setContenu($contenu);
            $traceRepository->save($trace, true);
            return array('success' => true);
        } else {
            $error = 'Le contenu n\'est pas un lien valide';
            return array('success' => false, 'error' => $error);
        }    }
}