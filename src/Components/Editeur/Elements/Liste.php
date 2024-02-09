<?php

namespace App\Components\Editeur\Elements;


use App\Components\Editeur\Form\ListeType;
use App\Components\Editeur\Form\ParagrapheType;
use App\Entity\Element;
use App\Repository\ElementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;
use function PHPUnit\Framework\isEmpty;

class Liste extends AbstractElement
{
    public const NAME = 'liste';
    public const CATEGORY = "text";
    public const COLOR = 'red';
    public const TEMPLATE = 'liste.html.twig';
    public const FORM = ListeType::class;
    public const ICON = 'fas fa-list';

    private string $texte;
    public string $name = 'liste';
    public string $block_name = 'type_liste';

    public function __construct(ElementRepository $elementRepository, Environment $twig)
    {
        parent::__construct($elementRepository, $twig);
        $this->elementRepository = $elementRepository;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver
            ->setDefault('block_name', 'type_liste')
            ->setDefault('type_element', 'liste');
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

        if (!isset($data['liste'])) {
            $contenu = ["Liste"];
        } else {
            $contenu = $data['liste']['contenu'];

            if (empty($contenu) || (count($contenu) === 1 && $contenu[0] === "")) {
                $contenu = ["Liste"];
            }
        }

        $elementEntity->setEdit(false);
        $elementEntity->setContenuArray($contenu);
        $this->elementRepository->save($elementEntity);
    }
}