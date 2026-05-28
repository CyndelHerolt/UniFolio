<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */

namespace App\Twig\Components;

use App\Controller\BaseController;
use App\Entity\ApcNiveau;
use App\Entity\Etudiant;
use App\Entity\Groupe;
use App\Entity\Semestre;
use App\Entity\Trace;
use App\Repository\AnneeRepository;
use App\Repository\ApcNiveauRepository;
use App\Repository\CommentaireRepository;
use App\Repository\CompetenceRepository;
use App\Repository\DepartementRepository;
use App\Repository\EnseignantDepartementRepository;
use App\Repository\EtudiantRepository;
use App\Repository\GroupeRepository;
use App\Repository\SemestreRepository;
use App\Repository\TraceRepository;
use App\Repository\TypeGroupeRepository;
use App\Repository\ValidationRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use App\Classes\DataUserSession;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsLiveComponent('AllTraceEvalComponent')]
final class AllTraceEvalComponent extends BaseController
{
    use DefaultActionTrait;

    public array $semestre = [];
    public array $annee = [];
    public array $dept = [];
    public int $validation = 0;

    #[LiveProp]
    public bool $initialized = false;

    public int $itemsPerPage = 4;

    #[LiveProp(writable: true)]
    public int $currentPage = 1;

    #[LiveProp(writable: true)]
    /** @var Groupe[] */
    public array $groupes = [];

    #[LiveProp(writable: true)]
    /** @var Etudiant[] */
    public array $etudiants = [];

    #[LiveProp(writable: true)]
    public ?int $selectedSemestreId = null;

    // Propriété interne non sérialisée
    private ?Semestre $selectedSemestre = null;

    #[LiveProp(writable: true)]
    public array $selectedCompetences = [];

    #[LiveProp(writable: true)]
    public array $selectedGroupes = [];

    #[LiveProp(writable: true)]
    public array $selectedEtudiants = [];

    #[LiveProp(writable: true)]
    public ?int $selectedEtat = 2;

    #[LiveProp(writable: true)]
    /** @var ApcNiveau[] */
    public array $niveaux = [];

    #[LiveProp(writable: true)]
    /** @var Trace[] */
    public array $allTraces = [];

    public function __construct(
        public TraceRepository $traceRepository,
        public DepartementRepository $departementRepository,
        public CompetenceRepository $competenceRepository,
        public ApcNiveauRepository $apcNiveauRepository,
        public EtudiantRepository $etudiantRepository,
        public GroupeRepository $groupeRepository,
        public SemestreRepository $semestreRepository,
        public AnneeRepository $anneeRepository,
        public TypeGroupeRepository $typeGroupeRepository,
        public ValidationRepository $validationRepository,
        public CommentaireRepository $commentaireRepository,
        #[Required] public Security $security,
        RequestStack $requestStack,
        private FormFactoryInterface $formFactory,
        protected DataUserSession $dataUserSession,
        protected EnseignantDepartementRepository $enseignantDepartementRepository,
    ) {
        $this->requestStack = $requestStack;

        $user = $this->security->getUser()->getEnseignant();
        $dept = $this->departementRepository->findDepartementEnseignantDefaut($user);

        foreach ($dept as $departement) {
            $this->annees = $this->anneeRepository->findByDepartement($departement);
            $this->semestres = $this->semestreRepository->findByDepartementActif($departement);

            foreach ($this->semestres as $semestre) {
                $groupes = $this->groupeRepository->findBySemestre($semestre);
                foreach ($groupes as $groupe) {
                    $this->groupes[] = $groupe;
                }
                $this->groupes = array_unique($this->groupes, SORT_REGULAR);
            }

            foreach ($this->semestres as $semestre) {
                $etudiants = $this->etudiantRepository->findBySemestre($semestre);
                foreach ($etudiants as $etudiant) {
                    $this->etudiants[] = $etudiant;
                }
            }
            $this->dept[] = $departement;
        }
    }

    private function resolveSelectedSemestre(): void
    {
        $this->selectedSemestre = $this->selectedSemestreId
            ? $this->semestreRepository->find($this->selectedSemestreId)
            : null;
    }

    #[PostMount]
    public function init()
    {
        if (!$this->initialized) {
            $this->initialized = true;
            $this->resolveSelectedSemestre();
            $this->changeSemestre($this->selectedSemestreId ?? 0);
        }
    }

    #[LiveAction]
    public function changeEtat(#[LiveArg] int $id = 0)
    {
        $this->currentPage = 1;
        $this->allTraces = [];
        $this->resolveSelectedSemestre();

        switch ($id) {
            case 0:
                $this->selectedEtat = 0;
                break;
            case 1:
                $this->selectedEtat = 1;
                break;
            case 2:
                $this->selectedEtat = 2;
                break;
        }

        $this->getDisplayedTraces();
    }

    #[LiveAction]
    public function changeCompetences()
    {
        $this->currentPage = 1;
        $this->etudiants = [];
        $this->resolveSelectedSemestre();

        $competences = $this->apcNiveauRepository->findBy(['id' => $this->selectedCompetences]);

        $this->groupes = [];
        foreach ($competences as $competence) {
            $ordre = $competence->getOrdre();
            $semestres = $this->semestreRepository->findBy(['id' => $this->semestres]);

            $annees = [];
            foreach ($semestres as $semestre) {
                $annees[] = $semestre->getAnnee();
                foreach ($annees as $annee) {
                    if ($annee->getOrdre() == $ordre) {
                        $semestres = $annee->getSemestres();
                    }
                }
            }
            foreach ($semestres as $semestre) {
                $groupes = $this->groupeRepository->findBySemestre($semestre);
                foreach ($groupes as $groupe) {
                    $this->groupes[] = $groupe;
                }
            }

            foreach ($this->groupes as $groupe) {
                $etudiants = $groupe->getEtudiants();
                foreach ($etudiants as $etudiant) {
                    $this->etudiants[] = $etudiant;
                }
                $this->etudiants = array_unique($this->etudiants, SORT_REGULAR);
            }
        }

        if ($competences == null) {
            foreach ($this->semestres as $semestre) {
                $etudiants = $this->etudiantRepository->findBySemestre($semestre);
                foreach ($etudiants as $etudiant) {
                    $this->etudiants[] = $etudiant;
                }
                $groupes = $this->groupeRepository->findBySemestre($semestre);
                foreach ($groupes as $groupe) {
                    $this->groupes[] = $groupe;
                }
            }
        }

        $this->getDisplayedTraces();
    }

    #[LiveAction]
    public function changeGroupes()
    {
        $this->currentPage = 1;
        $this->resolveSelectedSemestre();

        $groupes = $this->groupeRepository->findBy(['id' => $this->selectedGroupes]);

        $this->etudiants = [];
        $this->niveaux = [];
        $competencesNiveau = [];
        $dept = $this->dataUserSession->getDepartement();
        $referentiel = $dept->getApcReferentiels();
        $competences = $this->competenceRepository->findBy(['referentiel' => $referentiel->first()]);

        foreach ($groupes as $groupe) {
            foreach ($groupe->getEtudiants() as $etudiant) {
                $this->etudiants[] = $etudiant;
            }

            $semestres = $groupe->getTypeGroupe()->getSemestre();
            foreach ($semestres as $semestre) {
                if ($groupe->getApcParcours() !== null) {
                    if ($semestre->getAnnee()->getDiplome()->getApcParcours() == $groupe->getApcParcours()) {
                        $annee = $semestre->getAnnee();
                        $parcours = $groupe->getApcParcours();
                        $niveaux = $this->apcNiveauRepository->findByAnneeParcours($annee, $parcours);
                        $competencesNiveau = array_merge($competencesNiveau, $niveaux);
                        $competencesNiveau = array_unique($competencesNiveau, SORT_REGULAR);
                    }
                } else {
                    foreach ($competences as $competence) {
                        $niveaux = $this->apcNiveauRepository->findByAnnee($competence, $semestre->getAnnee()->getOrdre());
                        $competencesNiveau = array_merge($competencesNiveau, $niveaux);
                        $competencesNiveau = array_unique($competencesNiveau, SORT_REGULAR);
                    }
                }
                $this->niveaux = $competencesNiveau;
            }
        }

        if ($groupes == null) {
            foreach ($this->semestres as $semestre) {
                $etudiants = $this->etudiantRepository->findBySemestre($semestre);
                foreach ($etudiants as $etudiant) {
                    $this->etudiants[] = $etudiant;
                }
                foreach ($competences as $competence) {
                    $niveaux = $this->apcNiveauRepository->findByAnnee($competence, $semestre->getAnnee()->getOrdre());
                    $competencesNiveau = array_merge($competencesNiveau, $niveaux);
                    $competencesNiveau = array_unique($competencesNiveau, SORT_REGULAR);
                }
                $this->niveaux = $competencesNiveau;
            }
        }

        $this->getDisplayedTraces();
    }

    #[LiveAction]
    public function changeEtudiants()
    {
        $this->currentPage = 1;
        $this->resolveSelectedSemestre();

        $etudiants = $this->etudiantRepository->findBy(['id' => $this->selectedEtudiants]);
        $this->groupes = [];
        foreach ($etudiants as $etudiant) {
            if ($etudiant->getGroupe() !== null) {
                foreach ($etudiant->getGroupe() as $groupe) {
                    if ($groupe->getTypeGroupe()->getType() === "TP") {
                        $this->groupes[] = $groupe;
                    }
                }
            }
        }
        if ($etudiants == null) {
            foreach ($this->semestres as $semestre) {
                $groupes = $this->groupeRepository->findBySemestre($semestre);
                foreach ($groupes as $groupe) {
                    $this->groupes[] = $groupe;
                }
            }
        }

        $this->getDisplayedTraces();
    }

    #[LiveAction]
    public function changeSemestre(#[LiveArg] int $id = 0)
    {
        $this->currentPage = 1;

        if ($id !== null && $id !== 0) {
            $this->selectedSemestreId = $id;
            $this->selectedSemestre = $this->semestreRepository->find($id);
        } else {
            $this->selectedSemestreId = null;
            $this->selectedSemestre = null;
        }

        $user = $this->security->getUser()->getEnseignant();
        $dept = $this->departementRepository->findDepartementEnseignantDefaut($user);
        $referentiel = null;
        foreach ($dept as $departement) {
            $referentiel = $departement->getApcReferentiels()->first();
        }
        $competences = $this->competenceRepository->findBy(['referentiel' => $referentiel]);
        $competencesNiveau = [];

        if ($this->selectedSemestre !== null) {
            $semestre = $this->selectedSemestre;
            $this->groupes = $this->groupeRepository->findBySemestre($semestre);
            $this->niveaux = [];

            foreach ($this->groupes as $groupe) {
                if ($groupe->getApcParcours() !== null) {
                    if ($groupe->getApcParcours() === $semestre->getAnnee()->getDiplome()->getApcParcours()) {
                        $parcours = $groupe->getApcParcours();
                        $annee = $semestre->getAnnee();
                        $niveaux = $this->apcNiveauRepository->findByAnneeParcours($annee, $parcours);
                        $competencesNiveau = $niveaux;
                    }
                } else {
                    foreach ($competences as $competence) {
                        $niveaux = $this->apcNiveauRepository->findByAnnee($competence, $semestre->getAnnee()->getOrdre());
                        $competencesNiveau = array_merge($competencesNiveau, $niveaux);
                        $competencesNiveau = array_unique($competencesNiveau, SORT_REGULAR);
                    }
                }
                $this->niveaux = $competencesNiveau;
            }

            $this->etudiants = $this->etudiantRepository->findBySemestre($semestre);
        } else {
            foreach ($this->semestres as $semestre) {
                foreach ($competences as $competence) {
                    $niveaux = $this->apcNiveauRepository->findByAnnee($competence, $semestre->getAnnee()->getOrdre());
                    $competencesNiveau = array_merge($competencesNiveau, $niveaux);
                    $competencesNiveau = array_unique($competencesNiveau, SORT_REGULAR);
                }
            }
            $this->niveaux = $competencesNiveau;
        }

        if ($this->selectedEtudiants !== null) {
            foreach ($this->selectedEtudiants as $selectedEtudiant) {
                $etudiant = $this->etudiantRepository->find($selectedEtudiant);
                if (!in_array($etudiant, $this->etudiants)) {
                    $this->selectedEtudiants = [];
                }
            }
        }
        if ($this->selectedGroupes !== null) {
            foreach ($this->selectedGroupes as $selectedGroupe) {
                $groupe = $this->groupeRepository->find($selectedGroupe);
                if (!in_array($groupe, $this->groupes)) {
                    $this->selectedGroupes = [];
                }
            }
        }
        if ($this->selectedCompetences !== null) {
            foreach ($this->selectedCompetences as $selectedCompetence) {
                $competence = $this->apcNiveauRepository->find($selectedCompetence);
                if (!in_array($competence, $this->niveaux)) {
                    $this->selectedCompetences = [];
                }
            }
        }

        $this->getDisplayedTraces();
    }

    public function getTotalPages()
    {
        $count = count($this->getAllTrace());
        return intval(ceil($count / $this->itemsPerPage));
    }

    public function getDisplayedTraces()
    {
        $offset = ($this->currentPage - 1) * $this->itemsPerPage;
        $traces = $this->getAllTrace();
        $this->allTraces = array_slice($traces, $offset, $this->itemsPerPage);
    }

    #[LiveAction]
    public function goNextPage()
    {
        $this->resolveSelectedSemestre();
        if ($this->currentPage < $this->getTotalPages()) {
            $this->currentPage++;
        }
        $this->getDisplayedTraces();
    }

    #[LiveAction]
    public function goPreviousPage()
    {
        $this->resolveSelectedSemestre();
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
        $this->getDisplayedTraces();
    }

    #[LiveAction]
    public function goToFirstPage()
    {
        $this->resolveSelectedSemestre();
        $this->currentPage = 1;
        $this->getDisplayedTraces();
    }

    #[LiveAction]
    public function goToLastPage()
    {
        $this->resolveSelectedSemestre();
        $this->currentPage = $this->getTotalPages();
        $this->getDisplayedTraces();
    }

    public function getAllTrace()
    {
        $this->resolveSelectedSemestre();
        $user = $this->getUser();
        $enseignant = $user->getEnseignant();

        $dept = $this->enseignantDepartementRepository->findOneBy(['enseignant' => $enseignant, 'defaut' => 1]);

        $traces = $this->traceRepository->findByFilters(
            $dept->getDepartement(),
            $this->selectedSemestre,
            $this->selectedCompetences,
            $this->selectedGroupes,
            $this->selectedEtudiants,
            $this->selectedEtat
        );

        if ($traces == null) {
            $this->currentPage = 0;
        }

        return $traces;
    }
}
