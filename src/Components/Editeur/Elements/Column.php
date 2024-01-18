<?php

namespace App\Components\Editeur\Elements;

use App\Entity\Element;
use App\Repository\ElementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Column extends AbstractElement
{
    public const NAME = 'colonne';
    public const CATEGORY = "colonne";
    public const COLOR = 'yellow';
    public const TEMPLATE = 'colonne.html.twig';
    public const ICON = 'fas fa-columns-3';

    private string $colonne;
    public string $name = 'colonne';

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