<?php


namespace App\Components\Trace\TypeTrace;

use App\Components\Trace\Form\TraceTypePdfType;
use App\Repository\TraceRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TraceTypePdf extends AbstractTrace implements TraceInterface
{
    final public const TAG_TYPE_TRACE = 'pdf';
    final public const FORM = TraceTypePdfType::class;
    final public const HELP = 'Upload de pdf : Taille maximale XMo';
    final public const ICON = 'fa-solid fa-3x fa-file-pdf';
    final public const TEMPLATE = 'Components/Trace/type/pdf.html.twig';
    final public const ID = '3';
    private $params;


    public function __construct(protected TraceRepository $traceRepository, ParameterBagInterface $params)
    {
        $this->type_trace = 'TraceTypePdf';
        $this->params = $params;
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
        // 8Mo en octets
        $max_size = 8 * 1024 * 1024;
        $pdfFiles = $form['contenu']->getData();

        $contenu = [];
        if ($existingContenu != null) {
            foreach ($existingContenu as $content) {
                $contenu[] = $content;
            }
        }

        if ($pdfFiles) {

            foreach ($pdfFiles as $pdfFile) {
                $pdfFileName = uniqid() . '.' . $pdfFile->guessExtension();
                //Vérifier si le fichier est au bon format
                if ($pdfFile->getSize() > $max_size) {
                    $error = 'Le fichier doit faire 8mo maximum';
                    return array('success' => false, 'error' => $error);
                }
                if ($pdfFile->guessExtension() === 'pdf') {
                    //Déplacer le fichier dans le dossier déclaré sous le nom ../www-datas dans services.yaml
                    $pdfFile->move($_ENV['PATH_FILES'], $pdfFileName);
                    $contenu[] = $_ENV['PATH_FILES'].'/'. $pdfFileName;
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
        $traceRepository->save($trace, true);
        return array('success' => true);
    }
}