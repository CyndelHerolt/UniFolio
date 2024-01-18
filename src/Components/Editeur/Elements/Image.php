<?php

namespace App\Components\Editeur\Elements;

use App\Entity\Element;
use App\Repository\ElementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Image extends AbstractElement
{
    public const NAME = 'image';
    public const CATEGORY = "media";
    public const COLOR = 'blue';
    public const TEMPLATE = 'image.html.twig';
    public const ICON = 'fas fa-image';

    private string $texte;
    public string $name = 'image';

    public function create($typeElement, $bloc): Response
    {
        parent::create($typeElement, $bloc);
        return new Response();
    }

    public function sauvegarde(
        AbstractElement $element,
        Request $request,
    )
    {
        dump($request->request->all());

    }
}