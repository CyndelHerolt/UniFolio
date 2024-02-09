<?php

namespace App\Components\Editeur;

use App\Components\Editeur\Elements\AbstractElement;
use App\Entity\Element;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;
use Twig\TemplateWrapper;

class TypeElementFormRenderer
{
    public ?TemplateWrapper $templateWrapper = null;
    private readonly string $template;

    public function __construct(
        protected EditeurRegistry    $editeurRegistry,
        public Environment           $twig,
        private FormFactoryInterface $formFactory
    )
    {
        $this->template = 'components/editeur/creation/blocks_type_element.html.twig';
    }

    public function render(
        AbstractElement $element,
        Element $elementEntity,
    ): string
    {
        $template = $this->load();

        $formType = $this->editeurRegistry->getForm($element);
        $form = $this->formFactory->create($element::FORM, $formType);

        $params = $element->getOptions();
        $params['block_name'] = $element->block_name;
        $params['form'] = $form->createView();
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