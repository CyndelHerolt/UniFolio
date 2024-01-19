<?php

namespace App\Components\Editeur\Elements;


use App\Components\Editeur\Form\ParagrapheType;
use App\Repository\ElementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Paragraphe extends AbstractElement
{
    public const NAME = 'paragraphe';
    public const CATEGORY = "texte";
    public const COLOR = 'red';
    public const TEMPLATE = 'paragraphe.html.twig';
    public const FORM = ParagrapheType::class;
    public const ICON = 'fas fa-paragraph';

    private string $texte;
    public string $name = 'paragraphe';
    public string $block_name = 'type_paragraphe';

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver
            ->setDefault('block_name', 'type_paragraphe')
            ->setDefault('type_element', 'paragraphe');
    }

    public
    function create($typeElement, $bloc): void
    {
        parent::create($typeElement, $bloc);
    }

    public
    function sauvegarde(
        AbstractElement $element,
        Request         $request,
    )
    {

    }
}