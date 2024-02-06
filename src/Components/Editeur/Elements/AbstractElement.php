<?php

namespace App\Components\Editeur\Elements;

use App\Components\Editeur\Form\ElementType;
use App\Entity\Element;
use App\Repository\ElementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

abstract class AbstractElement
{
    public const NAME = 'abstract';
    public const COLOR = 'black';
    public const CATEGORY = "abstract";
    public const TEMPLATE = 'abstract.html.twig';
    public const FORM = ElementType::class;
    public const ICON = 'non_defini';

    public string $name = 'abstract';
    public string $block_name = 'type_abstract';
    public int $ordre;

    public array $options = [];

    public int $id;

    public function __construct(
        private ElementRepository $elementRepository,
        private Environment $twig,
    )
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('block_name', $this->block_name)
            ->setDefault('form', $this::FORM)
            ->setDefault('type_element', $this->name);
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getOption(string $name)
    {
        return $this->options[$name];
    }

    public function render(): string
    {
        // Use the Twig environment to render the template for this element.
        // You will need to inject the Twig environment into this class to use it here.
        return $this->twig->render($this::TEMPLATE, ['element' => $this]);
    }

    public function create($typeElement, $bloc): void
    {
        $element = new Element();
        $element->setType($typeElement);
        $element->setOrdre(count($bloc->getElements()) + 1);
        $element->setBloc($bloc);

        $this->elementRepository->save($element);
    }

    public function sauvegarde(
        AbstractElement $element,
        Request $request,
        Element $elementEntity,
    )
    {

    }
}