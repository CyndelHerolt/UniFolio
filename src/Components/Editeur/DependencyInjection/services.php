<?php
/*
 * Copyright (c) 2022. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/Sites/intranetV3/src/Components/Questionnaire/DependencyInjection/services.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 26/05/2022 18:11
 */

namespace App\Components\Editeur\DependencyInjection;

use App\Components\Editeur\EditeurRegistry;
use App\Components\Editeur\Elements\Column;
use App\Components\Editeur\Elements\Image;
use App\Components\Editeur\Elements\Paragraphe;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();
    $services->defaults()
        ->private()
        ->autowire()
        ->autoconfigure(false);

    $services->set(Image::class)->tag(EditeurRegistry::TAG_TYPE_ELEMENT);
    $services->set(Paragraphe::class)->tag(EditeurRegistry::TAG_TYPE_ELEMENT);
};