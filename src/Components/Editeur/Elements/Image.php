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
        if ($request->files->get('image') !== null) {
            $imageFile = $request->files->get('image')['contenu'];

            // TODO: upload le fichier sur le serveur + tests formats et poids
            $max_size = 2 * 1024 * 1024; // 2 Mo en octets
            if ($imageFile->getSize() > $max_size) {
                $error = 'Le fichier doit faire 2mo maximum';
                return array('success' => false, 'error' => $error);
            }

            $fileName = uniqid() . '.' . $imageFile->guessExtension();
            //Vérifier si le fichier est au bon format
            if (in_array($imageFile->guessExtension(), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {
                $imageFile->move($_ENV['PATH_FILES'], $fileName);
                $contenu = $_ENV['SRC_FILES'] . '/' . $fileName;
            } else {
                $error = 'Le fichier n\'est pas au bon format - formats acceptés : jpg, jpeg, png, gif, svg, webp';
                return array('success' => false, 'error' => $error);
            }
            if (empty($contenu)) {
                $error = 'Veuillez ajouter un fichier';
                return array('success' => false, 'error' => $error);
            }


            $elementEntity->setContenu($contenu);
            $elementEntity->setEdit(false);
            $this->elementRepository->save($elementEntity);
        } else {
            $error = 'Veuillez ajouter un fichier';
            return array('success' => false, 'error' => $error);
        }
    }
}