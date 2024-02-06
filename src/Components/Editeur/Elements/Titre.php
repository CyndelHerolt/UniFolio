<?php

namespace App\Components\Editeur\Elements;

use App\Components\Editeur\Form\TitreType;
use App\Entity\Element;
use App\Repository\ElementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

class Titre extends AbstractElement
{
    public const NAME = 'titre';
    public const CATEGORY = "text";
    public const COLOR = 'blue';
    public const TEMPLATE = 'titre.html.twig';
    public const FORM = TitreType::class;
    public const ICON = 'fas fa-heading';

    private string $texte;
    public string $name = 'titre';
    public string $block_name = 'type_titre';

    public function __construct(ElementRepository $elementRepository, Environment $twig)
    {
        parent::__construct($elementRepository, $twig);
        $this->elementRepository = $elementRepository;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver
            ->setDefault('block_name', 'type_titre')
            ->setDefault('type_element', 'titre');
    }

    public
    function create($typeElement, $bloc): void
    {
        parent::create($typeElement, $bloc);
    }

    public function sauvegarde(
        AbstractElement $element,
        Request         $request,
        Element         $elementEntity,
    )
    {
        $data = $request->request->all();
        $contenu = $data['titre']['contenu'];

        $elementEntity->setEdit(false);
        $elementEntity->setContenu($contenu);
        $this->elementRepository->save($elementEntity);
    }
}
