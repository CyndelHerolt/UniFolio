<?php

namespace App\Twig\Components;

use App\Classes\DataUserSession;
use App\Controller\BaseController;
use App\Entity\Trace;
use App\Repository\ApcNiveauRepository;
use App\Repository\CompetenceRepository;
use App\Repository\DepartementRepository;
use App\Repository\EtudiantRepository;
use App\Repository\GroupeRepository;
use App\Repository\SemestreRepository;
use App\Repository\TraceRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('AllTraceEvalComponent')]
final class AllTraceEvalComponent extends BaseController
{
    use DefaultActionTrait;

    public array $competences = [];
    public array $groupes = [];
    public array $etudiants = [];

    #[LiveProp(writable: true)]
    public array $selectedCompetences = [];

    #[LiveProp(writable: true)]
    public array $selectedGroupes = [];

    #[LiveProp(writable: true)]
    public array $selectedEtudiants = [];

    #[LiveProp(writable: true)]
    /** @var Trace[] */
    public array $allTraces = [];

    public function __construct(
        public TraceRepository        $traceRepository,
        public DepartementRepository  $departementRepository,
        public CompetenceRepository   $competenceRepository,
        public ApcNiveauRepository    $apcNiveauRepository,
        public EtudiantRepository     $etudiantRepository,
        public GroupeRepository       $groupeRepository,
        public SemestreRepository     $semestreRepository,
        #[Required] public Security   $security,
        RequestStack                  $requestStack,
    )
    {
        //todo: à revoir
        $this->requestStack = $requestStack;
        $this->allTraces = $this->getAllTrace();

        $user = $this->security->getUser()->getEnseignant();

        $dept = $this->departementRepository->findDepartementEnseignantDefaut($user);
        foreach ($dept as $departement) {
        $referentiel = $departement->getApcReferentiels();
        $etudiants = $this->etudiantRepository->findByDepartementArray($departement);
        $groupes = $this->groupeRepository->findByDepartement($departement);
        $semestres = $this->semestreRepository->findByDepartementActif($departement);
        }

        $competences = $this->competenceRepository->findBy(['referentiel' => $referentiel->first()]);

        foreach ($competences as $competence) {
            $niveaux[] = $this->apcNiveauRepository->findBy(['competences' => $competence]);
        }

        foreach ($niveaux as $niveau) {
            foreach ($niveau as $niv) {
                $competencesNiveau[] = $niv;
            }
        }

        $this->competences = $competencesNiveau;
        $this->etudiants = $etudiants;
        $this->groupes = $groupes;
        $this->semestres = $semestres;
    }

    public function getAllTrace() {


        return [];
    }

}
