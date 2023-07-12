<?php

namespace App\Twig\Components;

use App\Classes\DataUserSession;
use App\Controller\BaseController;
use App\Entity\ApcNiveau;
use App\Entity\Trace;
use App\Repository\AnneeRepository;
use App\Repository\ApcNiveauRepository;
use App\Repository\CompetenceRepository;
use App\Repository\DepartementRepository;
use App\Repository\EtudiantRepository;
use App\Repository\GroupeRepository;
use App\Repository\SemestreRepository;
use App\Repository\TraceRepository;
use App\Repository\TypeGroupeRepository;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('AllTraceEvalComponent')]
final class AllTraceEvalComponent extends BaseController
{
    use DefaultActionTrait;

//    public array $niveaux = [];
    public array $groupes = [];
    public array $etudiants = [];
    public array $annee = [];
    public array $dept = [];

    #[LiveProp(writable: true)]
    public ?int $selectedAnnee = null;

    #[LiveProp(writable: true)]
    /** @var ApcNiveau[] */
    public array $niveaux = [];

    #[LiveProp(writable: true)]
    /** @var Trace[] */
    public array $allTraces = [];

    public function __construct(
        public TraceRepository       $traceRepository,
        public DepartementRepository $departementRepository,
        public CompetenceRepository  $competenceRepository,
        public ApcNiveauRepository   $apcNiveauRepository,
        public EtudiantRepository    $etudiantRepository,
        public GroupeRepository      $groupeRepository,
        public SemestreRepository    $semestreRepository,
        public AnneeRepository       $anneeRepository,
        public TypeGroupeRepository  $typeGroupeRepository,
        #[Required] public Security  $security,
        RequestStack                 $requestStack,
    )
    {
        $this->requestStack = $requestStack;
        $this->allTraces = $this->getAllTrace();

        $user = $this->security->getUser()->getEnseignant();
        $dept = $this->departementRepository->findDepartementEnseignantDefaut($user);
        foreach ($dept as $departement) {
            $this->annees = $this->anneeRepository->findByDepartement($departement);
            $this->groupes = $this->groupeRepository->findByDepartementSemestreActif($departement);
            $this->dept[] = $departement;
        }
        $this->changeAnnee($this->selectedAnnee);
    }

    #[LiveAction]
    public function changeAnnee(#[LiveArg] int $id = null)
    {
        $user = $this->security->getUser()->getEnseignant();
        $dept = $this->departementRepository->findDepartementEnseignantDefaut($user);
        $referentiel = null;
        foreach ($dept as $departement) {
            $referentiel = $departement->getApcReferentiels()->first();
        }

        $this->selectedAnnee = $id;

        $competencesNiveau = []; // Reset Competences Niveau array

        $competences = $this->competenceRepository->findBy(['referentiel' => $referentiel]);

        if ($this->selectedAnnee !== null) {
            $annee = $this->anneeRepository->findOneBy(['id' => $this->selectedAnnee]);
            foreach ($competences as $competence) {
                $niveaux = $this->apcNiveauRepository->findByAnnee($competence, $annee->getOrdre());
                $competencesNiveau = array_merge($competencesNiveau, $niveaux);
            }
        } else {
            foreach ($this->annees as $annee) {
                foreach ($competences as $competence) {
                    $niveaux = $this->apcNiveauRepository->findByAnnee($competence, $annee->getOrdre());
                    $competencesNiveau = array_merge($competencesNiveau, $niveaux);
                }
            }
        }

        $this->niveaux = $competencesNiveau;

        if ($this->selectedAnnee !== null) {
            $groupes = [];
            $annee = $this->anneeRepository->findOneBy(['id' => $this->selectedAnnee]);
            $semestres = $annee->getSemestres();
            foreach ($semestres as $semestre) {
                if ($this->semestreRepository->findOneBy(['id' => $semestre->getId(), 'actif' => true]) !== null) {
            $semestreActif = $this->semestreRepository->findOneBy(['id' => $semestre->getId(), 'actif' => true]);
            $parcours = $semestreActif->getAnnee()->getDiplome()->getApcParcours();
                    if ($parcours !== null) {
                        $groupes = $this->groupeRepository->findBy(['apcParcours' => $parcours]);
                    } else {
                        $groupes = $this->groupeRepository->findBySemestre($semestreActif);
                    }
                }
            }
            $this->groupes = $groupes;
        } else {
            $groupes = [];
            foreach ($this->annees as $annee) {
                $groupesPerAnnee = $this->groupeRepository->findByAnnee($annee);
                $this->groupes = array_merge($groupes, $groupesPerAnnee);
            }
        }

        $this->allTraces = $this->getAllTrace();
    }

    public function getAllTrace()
    {


        return [];
    }

}
