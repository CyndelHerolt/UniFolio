<?php

namespace App\Components\Editeur\Elements;

use App\Components\Editeur\Form\ImageType;
use App\Entity\Element;
use App\Repository\ElementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

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

    public function __construct(ElementRepository $elementRepository, Environment $twig)
    {
        parent::__construct($elementRepository, $twig);
        $this->elementRepository = $elementRepository;
    }

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
        Element $elementEntity,
    )
    {
        $data = $request->request->all();
        $contenu = $data['image']['contenu'];

        dd($request->files);

        // TODO: upload le fichier sur le serveur + tests formats et poids
        $max_size = 2 * 1024 * 1024; // 2 Mo en octets
        // Récupérer le fichier image



        $elementEntity->setContenu($contenu);
        $elementEntity->setEdit(false);
        $this->elementRepository->save($elementEntity);
    }

}