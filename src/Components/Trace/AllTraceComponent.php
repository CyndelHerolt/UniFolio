<?php
/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */

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

        $groupe = $user->getGroupe();
        foreach ($groupe as $g) {
            if ($g->getTypeGroupe()->getType() === 'TD') {
                $parcours = $g->getApcParcours();
            }
        }

        if ($parcours === null) {
            $referentiel = $dept->getApcReferentiels();

            $competences = $this->competenceRepository->findBy(['referentiel' => $referentiel->first()]);

            foreach ($competences as $competence) {
                $niveaux[] = $this->apcNiveauRepository->findByAnnee($competence, $annee->getOrdre());
                foreach ($niveaux as $niveau) {
                    foreach ($niveau as $niv) {
                        $competencesNiveaux[] = $niv;
                    }
                }
            }
            $competencesNiveauUnique = [];
            foreach ($competencesNiveaux as $niveau) {
                $competencesNiveauUnique[$niveau->getId()] = $niveau;
            }
            $competencesNiveau = $competencesNiveauUnique;
        } else {
            $niveaux = $this->apcNiveauRepository->findByAnneeParcours($annee, $parcours);
            foreach ($niveaux as $niveau) {
                $competencesNiveau[] = $niveau;
            }
        }

        $this->competences = $competencesNiveau;
    }

    #[LiveAction]
    public function changeCompetences()
    {
        $this->allTraces = $this->getAllTrace();
    }

    #[LiveAction]
    public function changeOrdreDate()
    {
        $this->selectedOrdreValidation = '';
        $this->allTraces = $this->getAllTrace();
    }

    #[LiveAction]
    public function changeOrdreValidation()
    {
        $this->selectedOrdreDate = '';
        $this->allTraces = $this->getAllTrace();
    }

    public function getAllTrace()
    {

        // Récupérer la bibliothèque de l'utilisateur connecté
        $etudiant = $this->security->getUser()->getEtudiant();
        $biblio = $this->bibliothequeRepository->findOneBy(['etudiant' => $etudiant]);

        // Vérifier si la requête n'est pas null (ce sera le cas si le composant est appelé hors d'une requête),
        // puis récupérer le paramètre 'competence' de la requête
        $competence = count($this->selectedCompetences) > 0 ? $this->selectedCompetences : null;

        $ordreDate = $this->selectedOrdreDate != null ? $this->selectedOrdreDate : null;

        $ordreValidation = $this->selectedOrdreValidation != null ? $this->selectedOrdreValidation : null;

        // On récupère les traces par compétence ou toutes les traces de la bibliothèque
        if (!empty($competence)) {
            $traces = $this->traceRepository->findByCompetence($competence);
        } elseif (!empty($competence) && !$this->traceRepository->findByCompetence($competence)) {
            $traces = [];
        } else {
            $traces = $this->traceRepository->findBy(['bibliotheque' => $biblio]);
        }

        // Si on a des traces, on peut les trier
        if (!empty($traces)) {

            // Trier par date si ordreDate est défini
            if (!empty($ordreDate)) {
                usort($traces, function (Trace $a, Trace $b) use ($ordreDate) {
                    if ($ordreDate === "ASC") {
                        return $a->getDateModification() <=> $b->getDateModification();
                    } else {
                        return $b->getDateModification() <=> $a->getDateModification();
                    }
                });
            }

            if (!empty($ordreValidation)) {
                usort($traces, function (Trace $a, Trace $b) use ($ordreValidation) {
                    $totalA = $a->getValidations()->count();
                    $totalB = $b->getValidations()->count();

                    $validationsA = $a->getValidations()->filter(function ($validation) {
                        return $validation->isEtat() == 3;
                    })->count();

                    $validationsB = $b->getValidations()->filter(function ($validation) {
                        return $validation->isEtat() == 3;
                    })->count();

                    // Ratio du nombre des validations avec un état de 3 sur le total des validations
                    $ratiosA = ($totalA > 0) ? $validationsA / $totalA : 0;
                    $ratiosB = ($totalB > 0) ? $validationsB / $totalB : 0;

                    if ($ordreValidation === "ASC") {
                        return $ratiosA <=> $ratiosB;
                    } else {
                        return $ratiosB <=> $ratiosA;
                    }
                });
            }
        }

        // Retirer les traces qui n'ont pas de type
        foreach ($traces as $key => $trace) {
            if ($trace->getTypeTrace() == null) {
                unset($traces[$key]);
            }
        }

        return $traces;
    }
}