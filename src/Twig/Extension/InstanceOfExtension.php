<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigTest;

class InstanceOfExtension extends AbstractExtension
{
    public function getTests(): array
    {
        return [
            new TwigTest(
                'instanceof',
                function ($var, $instance) {
                    $reflectionClass = new \ReflectionClass($instance);
                    return $reflectionClass->isInstance($var);
                }
            ),
        ];
    }
}
