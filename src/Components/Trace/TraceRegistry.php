<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Components\Trace;

use App\Components\Trace\TypeTrace\AbstractTrace;

class TraceRegistry
{
    public const TAG_TYPE_TRACE = 'type_trace';

    private array $typeTraces = [];

    public function registerTypeTrace(string $name, AbstractTrace $abstractTrace): void
    {
        //sauvegarde l'ensemble des types de trace dans un tableau associatif
        // /App/Components/Trace/TypeTrace/TraceTypeImage => TraceTypeImage (class)
        // /App/Components/Trace/TypeTrace/TraceTypeLien => TraceTypeLien (class)
        $this->typeTraces[$name] = $abstractTrace;
    }

    public function getTypeTrace(string $name): AbstractTrace
    {
        //tu récupères la classe (et donc un objet/variable) en fonction du nom de la casse (TraceTypeImage ou TraceTypeLien ou ...)
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

//    public function getIconTypeTrace(): array
//    {
//        //récupération de la constante TAG_TYPE_TRACE des classes de type de trace
//        // tableau vide
//        $tab = [];
//        // pour chaque type de trace, on récupère la constante TAG_TYPE_TRACE et on la met dans le tableau
//        foreach ($this->typeTraces as $name) {
//            $tab[$name::ICON] = $name::class;
//        }
//        // on retourne le tableau
//        return $tab;
//    }
}
