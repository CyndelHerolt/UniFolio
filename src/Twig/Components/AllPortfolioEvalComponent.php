<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */

namespace App\Twig\Components;

use App\Classes\DataUserSession;
use App\Controller\BaseController;
use App\Entity\ApcNiveau;
use App\Entity\EnseignantDepartement;
use App\Entity\Etudiant;
use App\Entity\Groupe;
use App\Entity\Portfolio;
use App\Entity\Semestre;
use App\Repository\AnneeRepository;
use App\Repository\ApcNiveauRepository;
use App\Repository\CommentaireRepository;
use App\Repository\CompetenceRepository;
use App\Repository\DepartementRepository;
use App\Repository\EnseignantDepartementRepository;
use App\Repository\EtudiantRepository;
use App\Repository\GroupeRepository;
use App\Repository\OrdrePageRepository;
use App\Repository\OrdreTraceRepository;
use App\Repository\PortfolioRepository;
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
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsLiveComponent('AllPortfolioEvalComponent')]
class AllPortfolioEvalComponent extends BaseController
{
    use DefaultActionTrait;

    public array $semestre = [];
    public array $annee = [];
    public array $dept = [];
    public int $validation = 0;

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
    /** @var Portfolio[] */
    public array $allPortfolios = [];

    public function __construct(
        protected PortfolioRepository             $portfolioRepository,
        protected DepartementRepository           $departementRepository,
        protected ApcNiveauRepository             $apcNiveauRepository,
        protected EtudiantRepository              $etudiantRepository,
        protected GroupeRepository                $groupeRepository,
        protected SemestreRepository              $semestreRepository,
        protected AnneeRepository                 $anneeRepository,
        protected TypeGroupeRepository            $typeGroupeRepository,
        protected ValidationRepository            $validationRepository,
        protected CommentaireRepository           $commentaireRepository,
        protected CompetenceRepository            $competenceRepository,
        protected OrdreTraceRepository            $ordreTraceRepository,
        protected OrdrePageRepository             $ordrePageRepository,
        protected TraceRepository                 $traceRepository,
        protected EnseignantDepartementRepository $enseignantDepartementRepository,
        #[Required] public Security               $security,
        RequestStack                              $requestStack,
        private FormFactoryInterface              $formFactory,
        protected DataUserSession                 $dataUserSession,
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

    // ✅ Résolution de l'entité depuis l'ID
    private function resolveSelectedSemestre(): void
    {
        $this->selectedSemestre = $this->selectedSemestreId !== null
            ? $this->semestreRepository->find($this->selectedSemestreId)
            : null;
    }

    #[PostMount]
    public function init(): void
    {
        $this->resolveSelectedSemestre();
        $this->changeSemestre($this->selectedSemestreId ?? 0);
    }

    #[LiveAction]
    public function changeEtat(#[LiveArg] int $id = 0): void
    {
        $this->currentPage = 1;
        $this->allPortfolios = [];

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

        $this->resolveSelectedSemestre();
        $this->getDisplayedPortfolios();
    }

    #[LiveAction]
    public function changeSemestre(#[LiveArg] int $id = 0): void
    {
        $this->currentPage = 1;

        // ✅ On stocke l'ID, pas l'entité
        $this->selectedSemestreId = $id !== 0 ? $id : null;
        $this->resolveSelectedSemestre();

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
            $this->etudiants = $this->etudiantRepository->findBySemestre($semestre);

            foreach ($competences as $competence) {
                $niveaux = $this->apcNiveauRepository->findByAnnee($competence, $semestre->getAnnee()->getOrdre());
                $competencesNiveau = array_merge($competencesNiveau, $niveaux);
                $competencesNiveau = array_unique($competencesNiveau, SORT_REGULAR);
            }
            $this->niveaux = $competencesNiveau;
        } else {
            $this->groupes = [];
            $this->etudiants = [];

            foreach ($this->semestres as $semestre) {
                $groupes = $this->groupeRepository->findBySemestre($semestre);
                foreach ($groupes as $groupe) {
                    $this->groupes[] = $groupe;
                }
                $this->groupes = array_unique($this->groupes, SORT_REGULAR);

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

        // Validation des sélections existantes
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

        $this->getDisplayedPortfolios();
    }

    #[LiveAction]
    public function changeCompetences(): void
    {
        $this->currentPage = 1;
        $this->resolveSelectedSemestre();
        $this->getDisplayedPortfolios();

        if ($this->selectedSemestre !== null) {
            $this->changeSemestre($this->selectedSemestreId);
        }
    }

    #[LiveAction]
    public function changeGroupes(): void
    {
        $this->currentPage = 1;
        $this->resolveSelectedSemestre();
        $this->getDisplayedPortfolios();
    }

    #[LiveAction]
    public function changeEtudiants(): void
    {
        $this->currentPage = 1;
        $this->resolveSelectedSemestre();
        $this->getDisplayedPortfolios();
    }

    public function getTotalPages(): int
    {
        $count = count($this->getAllPortfolio());
        return intval(ceil($count / $this->itemsPerPage));
    }

    public function getDisplayedPortfolios(): void
    {
        $offset = ($this->currentPage - 1) * $this->itemsPerPage;
        $portfolios = $this->getAllPortfolio();
        $this->allPortfolios = array_slice($portfolios, $offset, $this->itemsPerPage);
    }

    #[LiveAction]
    public function goNextPage(): void
    {
        $this->resolveSelectedSemestre();
        if ($this->currentPage < $this->getTotalPages()) {
            $this->currentPage++;
        }
        $this->getDisplayedPortfolios();
    }

    #[LiveAction]
    public function goPreviousPage(): void
    {
        $this->resolveSelectedSemestre();
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
        $this->getDisplayedPortfolios();
    }

    #[LiveAction]
    public function goToFirstPage(): void
    {
        $this->resolveSelectedSemestre();
        $this->currentPage = 1;
        $this->getDisplayedPortfolios();
    }

    #[LiveAction]
    public function goToLastPage(): void
    {
        $this->resolveSelectedSemestre();
        $this->currentPage = $this->getTotalPages();
        $this->getDisplayedPortfolios();
    }

    public function getAllPortfolio(): array
    {
        $this->resolveSelectedSemestre();

        $user = $this->getUser();
        $enseignant = $user->getEnseignant();
        $dept = $this->enseignantDepartementRepository->findOneBy(['enseignant' => $enseignant, 'defaut' => 1]);

        $portfolios = $this->portfolioRepository->findByFilters(
            $dept->getDepartement(),
            $this->selectedSemestre,
            $this->selectedGroupes,
            $this->selectedEtudiants,
            $this->selectedCompetences,
            $this->selectedEtat
        );

        $this->etat = 0;
        foreach ($portfolios as $portfolio) {
            foreach ($portfolio->getOrdrePages() as $ordrePage) {
                foreach ($ordrePage->getPage()->getOrdreTraces() as $ordreTrace) {
                    foreach ($ordreTrace->getTrace()->getValidations() as $validation) {
                        if ($validation->isEtat() != 0) {
                            $this->etat++;
                        }
                    }
                }
            }
        }

        if ($portfolios == null) {
            $this->currentPage = 0;
        }

        return $portfolios;
    }
}
