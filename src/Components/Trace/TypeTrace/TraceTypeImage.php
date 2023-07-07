<?php

namespace App\Components\Trace\TypeTrace;

use App\Components\Trace\Form\TraceTypeImageType;
use App\Entity\Trace;
use App\Repository\TraceRepository;
use Symfony\Component\DomCrawler\Crawler;

class TraceTypeImage extends AbstractTrace implements TraceInterface
{
    final public const TAG_TYPE_TRACE = 'image';
    final public const FORM = TraceTypeImageType::class;
    final public const HELP = 'Upload d\'image - format accepté : jpg, jpeg, png, gif';
    final public const ICON = 'fa-solid fa-3x fa-image';
    final public const TEMPLATE = 'Components/Trace/type/image.html.twig';
    final public const ID = '1';

    public function __construct(protected TraceRepository $traceRepository)
    {
        $this->type_trace = 'TraceTypeImage';
    }

    public function display(): string
    {
        return self::TAG_TYPE_TRACE;
    }

    public function getId(): ?string
    {
        return self::ID;
    }

    public function getTypeTrace(): ?string
    {
        return $this->type_trace;
    }

    public function save($form, $trace, $traceRepository, $traceRegistry, $existingContenu): array
    {
        $max_size = 2 * 1024 * 1024; // 2 Mo en octets
        $imageFiles = $form['contenu']->getData();

            $contenu = [];
            if ($existingContenu != null) {
                foreach ($existingContenu as $content) {
                    $contenu[] = $content;
                }
            }

        if ($imageFiles) {

            foreach ($imageFiles as $imageFile) {
                // Vérifier si la taille de l'image est inférieure ou égale à 2 Mo
                if ($imageFile->getSize() > $max_size) {
                    $error = 'Le fichier doit faire 2mo maximum';
                    return array('success' => false, 'error' => $error);
                }
                $imageFileName = uniqid() . '.' . $imageFile->guessExtension();
                //Vérifier si le fichier est au bon format
                if (in_array($imageFile->guessExtension(), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {
                    //Déplacer le fichier dans le dossier déclaré sous le nom files_directory dans services.yaml
                    $imageFile->move('files_directory', $imageFileName);
                    $contenu[] = 'files_directory' . '/' . $imageFileName;
                } else {
                    $error = 'Le fichier n\'est pas au bon format';
                    return array('success' => false, 'error' => $error);
                }
            }
        }

        if (empty($contenu)) {
            $error = 'Veuillez ajouter un fichier';
            return array('success' => false, 'error' => $error);
        }

        $trace->setContenu($contenu);
        return array('success' => true);
    }
}
