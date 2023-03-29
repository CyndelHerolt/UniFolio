<?php

namespace App\Components\Trace\TypeTrace;

use App\Components\Trace\Form\TraceTypeImageType;
use App\Components\Trace\Form\TraceTypeLienType;
use App\Repository\TraceRepository;

class TraceTypeLien extends AbstractTrace implements TraceInterface
{

    final public const TAG_TYPE_TRACE = 'lien';
    final public const FORM = TraceTypeLienType::class;
    final public const HELP = 'Upload de lien - lien absolu';
    final public const ICON = 'fa-solid fa-3x fa-link';
    final public const TEMPLATE = 'Components/Trace/type/lien.html.twig';

    public function __construct(protected TraceRepository $traceRepository)
    {
        $this->type_trace = 'TraceTypeLien';
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
        foreach ($contenus as $contenu) {
            // Vérifier si le contenu est un lien valide
            if (!filter_var($contenu, FILTER_VALIDATE_URL)) {
                $error = 'Le contenu n\'est pas un lien valide';
                return array('success' => false, 'error' => $error);
            }
        }
                return array('success' => true);
    }
}
