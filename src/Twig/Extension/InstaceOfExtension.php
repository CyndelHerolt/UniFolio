<?php

namespace App\Twig;

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