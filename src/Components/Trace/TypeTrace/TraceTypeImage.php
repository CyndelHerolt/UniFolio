<?php

namespace App\Components\Trace\TypeTrace;

use App\Components\Trace\Form\TraceTypeImageType;
use App\Repository\TraceRepository;

class TraceTypeImage extends AbstractTrace implements TraceInterface
{
    final public const TAG_TYPE_TRACE = 'image';
    final public const FORM = TraceTypeImageType::class;
    final public const HELP = 'Upload d\'image - format accepté : jpg, jpeg, png, gif';
    final public const ICON = 'fas fa-link';
    final public const TEMPLATE = 'Components/Trace/type/image.html.twig';

    public function __construct(protected TraceRepository $traceRepository)
    {
    }

    public function display(): string
    {
        return self::HELP;
    }

    public function save($form, $trace, $traceRepository, $traceRegistry): bool
    {
        $imageFile = $form['contenu']->getData();
        if ($imageFile) {
            $imageFileName = uniqid() . '.' . $imageFile->guessExtension();
            //Vérifier si le fichier est au bon format
            if (in_array($imageFile->guessExtension(), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {
                //Déplacer le fichier dans le dossier déclaré sous le nom files_directory dans services.yaml
                $imageFile->move('files_directory', $imageFileName);
                //Sauvegarder le contenu dans la base de données
                $trace->setContenu('files_directory' . '/' . $imageFileName);
                $trace->setTypetrace('image');
                $traceRepository->save($trace, true);
                return true;
                //return $this->redirectToRoute('app_trace');
            } else {
                echo 'Le fichier n\'est pas au bon format';
                return false;
            }
        }
    }
}
