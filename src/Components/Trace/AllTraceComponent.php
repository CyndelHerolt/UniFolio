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
        dump($this->selectedCompetences);
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
        dump(count($this->selectedCompetences));
        $competence = count($this->selectedCompetences) > 0 ? $this->selectedCompetences : null;


        // Si un formulaire en méthode post est reçu
        if ($competence !== null) {
            if ($this->traceRepository->findByCompetence($competence) != null) {
                dump('ok 2');
                // On récupère les compétences sélectionnées
                $traces = $this->traceRepository->findByCompetence($competence);
            } else {
                // Sinon, on récupère toutes les traces et "msg d'erreur"
                $traces = $this->traceRepository->findBy(['bibliotheque' => $biblio]);
            }
        } else {
            $traces = $this->traceRepository->findBy(['bibliotheque' => $biblio]);
            // retirer les traces qui n'ont pas de type
            foreach ($traces as $key => $trace) {
                if ($trace->getTypeTrace() == null) {
                    unset($traces[$key]);
                }
            }
        }

        dump($traces);

        return $traces;
    }
}