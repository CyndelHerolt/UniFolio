<?php

namespace App\Components\Trace\TypeTrace;

use App\Components\Trace\Form\TraceTypeImageType;
use App\Repository\TraceRepository;

class TraceTypeImage extends AbstractTrace implements TraceInterface
{
    final public const TAG_TYPE_TRACE = 'image.s';
    final public const FORM = TraceTypeImageType::class;
    final public const HELP = 'Upload d\'image - format accepté : jpg, jpeg, png, gif';
    final public const ICON = 'fa-solid fa-3x fa-image';
    final public const TEMPLATE = 'Components/Trace/type/image.html.twig';

    public function __construct(protected TraceRepository $traceRepository)
    {
        $this->type_trace = 'TraceTypeImage';
    }

    public function display(): string
    {
        return self::TAG_TYPE_TRACE;
    }

    public function getTypeTrace(): ?string
    {
        return $this->type_trace;
    }

    public function save($form, $trace, $traceRepository, $traceRegistry): array
    {
        $imageFiles = $form['contenu']->getData();
        if ($imageFiles) {
            $contenu = [];
            foreach ($imageFiles as $imageFile) {
//            dd($imageFiles);
//            dd($imageFile);
                $imageFileName = uniqid() . '.' . $imageFile->guessExtension();
                //Vérifier si le fichier est au bon format
                if (in_array($imageFile->guessExtension(), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {
                    //Déplacer le fichier dans le dossier déclaré sous le nom files_directory dans services.yaml
                    $imageFile->move('files_directory', $imageFileName);
                    $contenu[] = 'files_directory' . '/' . $imageFileName;
//                    $contenu[] = $contenu;
//                    dd($contenu);
                } elseif (!in_array($imageFile->guessExtension(), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {
                    $error = 'Le fichier n\'est pas au bon format';
                    return array('success' => false, 'error' => $error);
                }
            }
//            dd($contenu);
            //Sauvegarder le contenu dans la base de données
            $trace->setContenu($contenu);
            $traceRepository->save($trace, true);
            return array('success' => true);
        } elseif (!$imageFiles) {
            $error = 'Aucun fichier n\'a été sélectionné';
            return array('success' => false, 'error' => $error);
        }
        $error = '';
        // Return an empty array if $imageFile is false
        return array('success' => false, 'error' => $error);
    }
}
