<?php

namespace App\Components\Editeur;

use App\Components\Editeur\Elements\AbstractElement;
use App\Entity\Element;
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
    Element $elementEntity
): string
{
    $template = $this->load();

    $params = $element->getOptions();
    $params['block_name'] = $element->block_name;
    $params['element'] = $element;
    $params['elementEntity'] = $elementEntity;

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