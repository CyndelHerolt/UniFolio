<?php

namespace App\Twig\Components;

use App\Controller\BaseController;
use App\Entity\ApcNiveau;
use App\Entity\Etudiant;
use App\Entity\Groupe;
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

    public array $annee = [];
    public array $dept = [];

    #[LiveProp(writable: true)]
    /** @var Groupe[] */
    public array $groupes = [];

    #[LiveProp(writable: true)]
    /** @var Etudiant[] */
    public array $etudiants = [];

    #[LiveProp(writable: true)]
    public ?int $selectedAnnee = null;

    #[LiveProp(writable: true)]
    public array $selectedCompetences = [];

    #[LiveProp(writable: true)]
    public array $selectedGroupes = [];

    #[LiveProp(writable: true)]
    public array $selectedEtudiants = [];

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
    public function changeCompetences()
    {
        $this->allTraces = $this->getAllTrace();
        $this->changeAnnee($this->selectedAnnee);
    }

    #[LiveAction]
    public function changeGroupes()
    {

        // récupérer les étudiants des groupes sélectionnés
        $groupes = $this->groupeRepository->findBy(['id' => $this->selectedGroupes]);
        $this->etudiants = [];
        foreach ($groupes as $groupe) {
            if ($groupe->getEtudiants() !== null) {
                foreach ($groupe->getEtudiants() as $etudiant) {
                    $this->etudiants[] = $etudiant;
                }
            }

        }

        $this->allTraces = $this->getAllTrace();
        $this->changeAnnee($this->selectedAnnee);
    }

    #[LiveAction]
    public function changeEtudiants()
    {
        $this->allTraces = $this->getAllTrace();
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
                    //supprimer les doublons du tableau
                    $competencesNiveau = array_unique($competencesNiveau, SORT_REGULAR);
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
                $groupes = $this->groupeRepository->findByAnnee($annee);
            }
            $this->groupes = $groupes;
        }

        if ($this->selectedAnnee !== null) {
            if ($this->selectedGroupes == null) {
                $etudiants = [];
                $groupes = $this->groupes;
                foreach ($groupes as $groupe) {
                    $etudiantsGroupe = $groupe->getEtudiants();
                    foreach ($etudiantsGroupe as $etudiant) {
                        $etudiants[] = $etudiant;
                        //supprimer les doublons du tableau
                        $etudiants = array_unique($etudiants, SORT_REGULAR);
                    }
                }
                $this->etudiants = $etudiants;
            }
        } else {
            if ($this->selectedGroupes == null) {
                $etudiants = [];
                foreach ($this->annees as $annee) {
                    $etudiants = $this->etudiantRepository->findByAnnee($annee);
                }
                $this->etudiants = $etudiants;
            }
        }


        $this->allTraces = $this->getAllTrace();
    }

    public function getAllTrace()
    {

        $competence = count($this->selectedCompetences) > 0 ? $this->selectedCompetences : null;

        if ($competence !== null && $this->selectedGroupes == null && $this->selectedEtudiants == null) {
            $traces = $this->traceRepository->findByCompetence($competence);
        } else {
            $traces = $this->traceRepository->findAll();
        }


        return $traces;
    }

}
