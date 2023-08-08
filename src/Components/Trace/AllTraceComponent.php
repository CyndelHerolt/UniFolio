<?php

namespace App\Components\Trace;

use App\Controller\BaseController;
use App\Entity\Trace;
use App\Repository\ApcNiveauRepository;
use App\Repository\BibliothequeRepository;
use App\Repository\CompetenceRepository;
use App\Repository\TraceRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('all_trace')]
class AllTraceComponent extends BaseController
{
    use DefaultActionTrait;

    public array $competences = [];

    #[LiveProp(writable: true)]
    public array $selectedCompetences = [];

    #[LiveProp(writable: true)]
    public string $selectedOrdreDate = '';

    #[LiveProp(writable: true)]
    public string $selectedOrdreValidation = '';

    #[LiveProp(writable: true)]
    /** @var Trace[] */
    public array $allTraces = [];

    public function __construct(
        public TraceRepository        $traceRepository,
        public BibliothequeRepository $bibliothequeRepository,
        public CompetenceRepository   $competenceRepository,
        public ApcNiveauRepository    $apcNiveauRepository,
        #[Required] public Security   $security,
        RequestStack                  $requestStack,
    )
    {
        $this->requestStack = $requestStack;
        $this->allTraces = $this->getAllTrace();


        $user = $this->security->getUser()->getEtudiant();

        $semestre = $user->getSemestre();
        $annee = $semestre->getAnnee();
        $diplome = $annee->getDiplome();
        $dept = $diplome->getDepartement();

        $referentiel = $dept->getApcReferentiels();

        $competences = $this->competenceRepository->findBy(['referentiel' => $referentiel->first()]);

        foreach ($competences as $competence) {
            $niveaux[] = $this->apcNiveauRepository->findByAnnee($competence, $annee->getOrdre());
        }

        foreach ($niveaux as $niveau) {
            foreach ($niveau as $niv) {
                $competencesNiveau[] = $niv;
            }
        }

        $this->competences = $competencesNiveau;
    }

    #[LiveAction]
    public function changeCompetences()
    {
//        dump($this->selectedCompetences);
        $this->allTraces = $this->getAllTrace();
    }

    #[LiveAction]
    public function changeOrdreDate()
    {
//        dd($this->selectedOrdreDate);
        $this->allTraces = $this->getAllTrace();
    }

    #[LiveAction]
    public function changeOrdreValidation()
    {
//        dd($this->selectedOrdreValidation);
        $this->allTraces = $this->getAllTrace();
    }

    public function getAllTrace()
    {

        // Récupérer la bibliothèque de l'utilisateur connecté
        $etudiant = $this->security->getUser()->getEtudiant();
        $biblio = $this->bibliothequeRepository->findOneBy(['etudiant' => $etudiant]);

        // Vérifier si la requête n'est pas null (ce sera le cas si le composant est appelé hors d'une requête),
        // puis récupérer le paramètre 'competence' de la requête
        $competence = [];
        $competence = count($this->selectedCompetences) > 0 ? $this->selectedCompetences : null;

        $ordreDate = $this->selectedOrdreDate != null ? $this->selectedOrdreDate : null;

        $ordreValidation = $this->selectedOrdreValidation != null ? $this->selectedOrdreValidation : null;


        // Si un formulaire en méthode post est reçu
        if ($competence !== null) {
            if ($this->traceRepository->findByCompetence($competence) != null) {
                dump('ok 2');
                // On récupère les compétences sélectionnées
                $traces = $this->traceRepository->findByCompetence($competence);
            } else {
                if ($ordreDate !== null) {
//                dd($ordreDate);
                    $traces = $this->traceRepository->findBy(['bibliotheque' => $biblio], ['date_modification' => $ordreDate]);
                } elseif ($ordreValidation !== null) {
                    // on récupère les traces de la bibliothèque de l'utilisateur connecté
                    $traces = $this->traceRepository->findBy(['bibliotheque' => $biblio]);
                    // on récupère les validations des traces
                    foreach ($traces as $key => $trace) {
                        $validation[] = $trace->getValidations();
                    }
                    // Si $ordreValidation = "ASC", les traces dont les validations ont le plus de propriétés etat == 3 seront en premier
                    if ($ordreValidation == "ASC") {
                        // on trie les validations par ordre croissant
                        sort($validation);
                        // on récupère les traces dont les validations sont triées
                        foreach ($validation as $key => $val) {
                            $traces[] = $val[$key]->getTrace();
                        }
                    } else {
                        // on trie les validations par ordre décroissant
                        rsort($validation);
                        // on récupère les traces dont les validations sont triées
                        foreach ($validation as $key => $val) {
                            $traces[] = $val[$key]->getTrace();
                        }
                    }

                } else {
                    $traces = $this->traceRepository->findBy(['bibliotheque' => $biblio], ['date_modification' => 'DESC']);
                }
            }
        } else {
            if ($ordreDate !== null) {
//                dd($ordreDate);
                $traces = $this->traceRepository->findBy(['bibliotheque' => $biblio], ['date_modification' => $ordreDate]);
//                dd($traces);
            } elseif ($ordreValidation !== null) {
                // On récupère les traces de la bibliothèque de l'utilisateur connecté
                $traces = $this->traceRepository->findBy(['bibliotheque' => $biblio]);

                // On trie les traces en fonction du nombre de validations avec état égal à 3
                usort($traces, function ($a, $b) use ($ordreValidation) {
                    $validationsA = $a->getValidations()->filter(function ($validation) {
                        return $validation->isEtat() == 3;
                    })->count(); // Compter le nombre de validations avec état égal à 3

                    $validationsB = $b->getValidations()->filter(function ($validation) {
                        return $validation->isEtat() == 3;
                    })->count(); // Compter le nombre de validations avec état égal à 3

                    // Comparer le nombre de validations avec état égal à 3 en fonction de l'ordre
                    if ($ordreValidation === "ASC") {
                        return $validationsA - $validationsB; // Inverser l'ordre pour ASC
                    } else {
                        return $validationsB - $validationsA; // Garder l'ordre pour DESC
                    }
                });
            } else {
                $traces = $this->traceRepository->findBy(['bibliotheque' => $biblio], ['date_modification' => 'DESC']);
            }
            // retirer les traces qui n'ont pas de type
            foreach ($traces as $key => $trace) {
                if ($trace->getTypeTrace() == null) {
                    unset($traces[$key]);
                }
            }
        }
//        dd($traces);
        return $traces;
    }
}