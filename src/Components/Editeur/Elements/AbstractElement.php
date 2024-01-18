<?php

namespace App\Components\Editeur\Elements;

use App\Entity\Element;
use App\Repository\ElementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractElement
{
    public const NAME = 'abstract';
    public const COLOR = 'black';
    public const CATEGORY = "abstract";
    public const TEMPLATE = 'abstract.html.twig';
    public const ICON = 'non_defini';

    public string $name;
    public int $ordre;

    public array $options = [];

    public function __construct(
        private ElementRepository $elementRepository,
    )
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'name' => static::NAME,
            'color' => static::COLOR,
        ]);
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getOption(string $name)
    {
        return $this->options[$name];
    }

    public function create($typeElement): Response
    {
        $element = new Element();
        $element->setType($typeElement);
        $element->setOrdre(1);

        $this->elementRepository->save($element);

        return new Response();
    }

    public function sauvegarde(
        AbstractElement $element,
        Request $request,
    )
    {

    }
}