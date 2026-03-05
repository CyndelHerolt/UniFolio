<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Components\Trace\DependencyInjection;

use App\Components\Trace\TraceRegistry;
use App\Components\Trace\TypeTrace\TraceTypeImage;
use App\Components\Trace\TypeTrace\TraceTypeLien;
use App\Components\Trace\TypeTrace\TraceTypePdf;
use App\Components\Trace\TypeTrace\TraceTypeVideo;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();
    $services->defaults()
        ->private()
        ->autowire()
        ->autoconfigure(false);


    $services->set(TraceTypePdf::class)->tag(TraceRegistry::TAG_TYPE_TRACE);
    $services->set(TraceTypeVideo::class)->tag(TraceRegistry::TAG_TYPE_TRACE);
    $services->set(TraceTypeImage::class)->tag(TraceRegistry::TAG_TYPE_TRACE);
    $services->set(TraceTypeLien::class)->tag(TraceRegistry::TAG_TYPE_TRACE);
};
