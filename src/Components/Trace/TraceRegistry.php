<?php


namespace App\Components\Trace;

use App\Components\Trace\TypeTrace\AbstractTrace;
use App\Components\Trace\TypeTrace\TraceTypeImage;
use App\Components\Trace\TypeTrace\TraceTypeLien;
use App\Components\Trace\TypeTrace\TraceTypePdf;
use App\Components\Trace\TypeTrace\TraceTypeVideo;
use App\Entity\Trace;

class TraceRegistry
{
    public const TAG_TYPE_TRACE = 'type_trace';

    private array $typeTraces = [];

//    private $traceTypeImageType;
//    private $traceTypeLienType;
//    private $traceTypePdfType;
//    private $traceTypeVideoType;

//    public function __construct(
//        TraceTypeImage $traceTypeImage,
//        TraceTypeLien $traceTypeLien,
//        TraceTypePdf $traceTypePdf,
//        TraceTypeVideo $traceTypeVideo
//    ) {
//        $this->traceTypeImageType = $traceTypeImage::FORM;
//        $this->traceTypeLienType = $traceTypeLien::FORM;
//        $this->traceTypePdfType = $traceTypePdf::FORM;
//        $this->traceTypeVideoType = $traceTypeVideo::FORM;
//    }
//
//    public function getForms()
//    {
//        return [
//            'image' => $this->traceTypeImageType,
//            'lien' => $this->traceTypeLienType,
//            'pdf' => $this->traceTypePdfType,
//            'video' => $this->traceTypeVideoType,
//        ];
//    }

    public function registerTypeTrace(string $name, AbstractTrace $abstractTrace): void
    {
        $this->typeTraces[$name] = $abstractTrace;
    }

    public function getTypeTrace(string $name): AbstractTrace
    {
        return $this->typeTraces[$name];
    }

    public function getTypeTraces(): array
    {
        return $this->typeTraces;
    }

    public function getChoiceTypeTraceName(): array
    {
        //récupération de la constante TAG_TYPE_TRACE des classes de type de trace
        // tableau vide
        $tab = [];
        // pour chaque type de trace, on récupère la constante TAG_TYPE_TRACE et on la met dans le tableau
        foreach ($this->typeTraces as $name) {
            $tab[$name::TAG_TYPE_TRACE] = $name::class;
        }
        // on retourne le tableau
        return $tab;
    }

//    public function getChoiceTypeForm(): array
//    {
//        //récupération de la constante FORM de chaque classe de type de trace
//        // tableau vide
//        $tab = [];
//        // pour chaque type de trace, on récupère la constante FORM et on la met dans le tableau
//        foreach ($this->typeTraces as $name) {
//            $tab[$name::FORM] = $name::class;
//        }
//// on retourne le tableau
//        return $tab;
//    }
}

