<?php

namespace App\Components\Editeur;

use App\Components\Editeur\Elements\AbstractElement;
use Twig\Environment;
use Twig\TemplateWrapper;

class TypeElementRenderer
{
    public ?TemplateWrapper $templateWrapper = null;
    private readonly string $template;

    public function __construct(public Environment $twig)
    {
        $this->template = 'components/editeur/blocks_type_element.html.twig';
    }

    public function render(
        AbstractElement $element,
        int|string|null $ordre = 0,
        int $loop = 0
    ): string
    {
        $template = $this->load();

        $params = $element->getOptions();
        $params['block_name'] = $element->block_name;

        return $template->renderBlock($params['block_name'], $params);
    }

    private function load(): TemplateWrapper
    {
        if (null === $this->templateWrapper) {
            $this->templateWrapper = $this->twig->load($this->template);
        }

        return $this->templateWrapper;
    }
}