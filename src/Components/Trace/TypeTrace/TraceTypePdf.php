<?php


namespace App\Components\Trace\TypeTrace;

use App\Components\Trace\Form\TraceTypePdfType;
use App\Repository\TraceRepository;

class TraceTypePdf extends AbstractTrace implements TraceInterface
{
    final public const TAG_TYPE_TRACE = 'pdf';
    final public const FORM = TraceTypePdfType::class;
    final public const HELP = 'Upload de pdf : Taille maximale XMo';
    final public const ICON = 'fa-solid fa-3x fa-file-pdf';
    final public const TEMPLATE = 'Components/Trace/type/pdf.html.twig';

    public function __construct(protected TraceRepository $traceRepository)
    {
        $this->type_trace = 'TraceTypePdf';
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
        $pdfFile = $form['contenu']->getData();
        if ($pdfFile) {
            $pdfFileName = uniqid() . '.' . $pdfFile->guessExtension();
            //Vérifier si le fichier est au bon format
            if ($pdfFile->guessExtension() === 'pdf') {
                //Déplacer le fichier dans le dossier déclaré sous le nom files_directory dans services.yaml
                $pdfFile->move('files_directory', $pdfFileName);
                //Sauvegarder le contenu dans la base de données
                $trace->setContenu('files_directory' . '/' . $pdfFileName);
                $traceRepository->save($trace, true);
                return array('success' => true);
                //return $this->redirectToRoute('app_trace');
            } else {
                $error = 'Le fichier n\'est pas au bon format';
                return array('success' => false, 'error' => $error);
            }
        }
        $error = 'Une erreur s\'est produite';
        // Return an empty array if $imageFile is false
        return array('success' => false, 'error' => $error);
    }
}