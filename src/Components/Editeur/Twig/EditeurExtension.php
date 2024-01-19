<?php

namespace App\Components\Editeur\Twig;

use App\Components\Editeur\TypeElementFormRenderer;
use App\Components\Editeur\TypeElementRenderer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EditeurExtension extends AbstractExtension
{
    public function __construct(
        private readonly TypeElementRenderer $typeElementRenderer,
        private readonly TypeElementFormRenderer $typeElementFormRenderer
    )
    {

    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('element_render', $this->typeElementRenderer->render(...), [
                'is_safe' => ['html'],
            ]),
            new TwigFunction('element_form_render', $this->typeElementFormRenderer->render(...), [
                'is_safe' => ['html'],
            ]),
        ];
    }
}