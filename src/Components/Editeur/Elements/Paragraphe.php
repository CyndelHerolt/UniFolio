<?php

namespace App\Components\Editeur\Elements;


use App\Repository\ElementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Paragraphe extends AbstractElement
{
    public const NAME = 'paragraphe';
    public const CATEGORY = "texte";
    public const COLOR = 'red';
    public const TEMPLATE = 'paragraphe.html.twig';
    public const ICON = 'fas fa-paragraph';

    private string $texte;
    public string $name = 'paragraphe';

    public function create($typeElement): Response
    {
        parent::create($typeElement);

        return new Response();
    }

    public function sauvegarde(
        AbstractElement $element,
        Request $request,
    )
    {

    }
}