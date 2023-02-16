<?php

namespace App\Components\Trace\TypeTrace;

use App\Components\Trace\Form\TraceTypeImageType;

class TraceTypeImage extends AbstractTrace implements TraceInterface
{
    final public const TAG_TYPE_TRACE = 'image';
    final public const FORM = TraceTypeImageType::class;
    final public const HELP = 'Upload d\'image - format accepté : jpg, jpeg, png, gif';
    final public const ICON = 'fas fa-link';
    final public const TEMPLATE = 'Components/Trace/type/image.html.twig';


    public function display(): string
    {
        return self::HELP;
    }

    public function save($form, $trace, $traceRepository, $directory): bool
    {
        $imageFile = $form['contenu']->getData();
        if ($imageFile) {
            $imageFileName = uniqid() . '.' . $imageFile->guessExtension();
            if (in_array($imageFile->guessExtension(), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {
                $imageFile->move($directory, $imageFileName);
                $trace->setContenu($directory . '/' . $imageFileName);
                $traceRepository->save($trace, true);
                return true;
                //return $this->redirectToRoute('app_trace');
            } else {
                echo 'Le fichier n\'est pas au bon format';
                return false;
            }
        }
        return true;
    }
}
