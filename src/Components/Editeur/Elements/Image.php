<?php

namespace App\Components\Editeur\Elements;

use App\Components\Editeur\Form\ImageType;
use App\Entity\Element;
use App\Repository\ElementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Image extends AbstractElement
{
    public const NAME = 'image';
    public const CATEGORY = "media";
    public const COLOR = 'blue';
    public const TEMPLATE = 'image.html.twig';
    public const FORM = ImageType::class;
    public const ICON = 'fas fa-image';

    private string $texte;
    public string $name = 'image';
    public string $block_name = 'type_image';

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver
            ->setDefault('block_name', 'type_image')
            ->setDefault('type_element', 'image');
    }

    public function create($typeElement, $bloc): void
    {
        parent::create($typeElement, $bloc);
    }

    public function sauvegarde(
        AbstractElement $element,
        Request $request,
    )
    {
        dump($request->request->all());

    }

}