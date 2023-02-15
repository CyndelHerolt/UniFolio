<?php

namespace App\Components\Trace\DependencyInjection;

use App\Components\Trace\TraceRegistry;
use App\Components\Trace\TypeTrace\TraceTypeFichier;
use App\Components\Trace\TypeTrace\TraceTypeLien;
use App\Components\Trace\TypeTrace\TraceTypeTexte;
use App\Components\Trace\TypeTrace\TraceTypeVideo;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();
    $services->defaults()
        ->private()
        ->autowire()
        ->autoconfigure(false);


    $services->set(TraceTypeTexte::class)->tag(TraceRegistry::TAG_TYPE_TRACE);
    $services->set(TraceTypeVideo::class)->tag(TraceRegistry::TAG_TYPE_TRACE);
    $services->set(TraceTypeFichier::class)->tag(TraceRegistry::TAG_TYPE_TRACE);
    $services->set(TraceTypeLien::class)->tag(TraceRegistry::TAG_TYPE_TRACE);
};