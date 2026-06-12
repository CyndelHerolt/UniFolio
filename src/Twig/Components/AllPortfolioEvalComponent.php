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
use App\Entity\Departement;
use App\Entity\Enseignant;
use App\Entity\Etudiant;
use App\Entity\Groupe;
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
    public array $semestres = [];
    public array $annees = [];
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
    /** @var int[] */
    public array $allPortfolios = [];

    private RequestStack $requestStack;
    private ?Departement $defaultDepartement = null;
    private ?Enseignant $currentEnseignant = null;
    private array $defaultCompetences = [];
    private ?array $allPortfolioIdsCache = null;
    private ?string $allPortfolioIdsCacheKey = null;
    private ?int $totalPortfoliosCache = null;
    private ?string $totalPortfoliosCacheKey = null;

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

        $securityUser = $this->security->getUser();
        $this->currentEnseignant = $securityUser?->getEnseignant();
        $dept = $this->currentEnseignant
            ? $this->departementRepository->findDepartementEnseignantDefaut($this->currentEnseignant)
            : [];

        foreach ($dept as $index => $departement) {
            if ($index === 0) {
                $this->defaultDepartement = $departement;
            }

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

        if ($this->defaultDepartement !== null) {
            $referentiel = $this->defaultDepartement->getApcReferentiels()->first();
            if ($referentiel !== false && $referentiel !== null) {
                $this->defaultCompetences = $this->competenceRepository->findBy(['referentiel' => $referentiel]);
            }
        }

        $this->groupes = $this->uniqueEntities($this->groupes);
        $this->etudiants = $this->uniqueEntities($this->etudiants);
    }

    private function resolveSelectedSemestre(): void
    {
        if ($this->selectedSemestreId === null) {
            $this->selectedSemestre = null;
            return;
        }

        if ($this->selectedSemestre !== null && $this->selectedSemestre->getId() === $this->selectedSemestreId) {
            return;
        }

        $this->selectedSemestre = $this->semestreRepository->find($this->selectedSemestreId);
    }

    private function uniqueEntities(array $entities): array
    {
        $unique = [];
        $seen = [];

        foreach ($entities as $entity) {
            if (!is_object($entity)) {
                continue;
            }

            $id = method_exists($entity, 'getId') ? $entity->getId() : null;
            $key = $entity::class . '#' . ($id ?? spl_object_hash($entity));

            if (isset($seen[$key])) {
                continue;
            }

            $seen[$key] = true;
            $unique[] = $entity;
        }

        return $unique;
    }

    private function appendUniqueEntities(array &$target, array $entities): void
    {
        $target = $this->uniqueEntities([...$target, ...$entities]);
    }

    private function selectedIdsExistInCollection(array $selectedIds, array $entities): bool
    {
        $availableIds = [];
        foreach ($entities as $entity) {
            if (is_object($entity) && method_exists($entity, 'getId') && $entity->getId() !== null) {
                $availableIds[] = (int) $entity->getId();
            }
        }

        foreach ($selectedIds as $id) {
            if (!in_array((int) $id, $availableIds, true)) {
                return false;
            }
        }

        return true;
    }

    private function resetPortfoliosCache(): void
    {
        $this->allPortfolioIdsCache = null;
        $this->allPortfolioIdsCacheKey = null;
        $this->totalPortfoliosCache = null;
        $this->totalPortfoliosCacheKey = null;
    }

    private function getFiltersCacheKey(): string
    {
        $state = [
            'dept' => $this->defaultDepartement?->getId(),
            'semestre' => $this->selectedSemestre?->getId(),
            'competences' => array_map('intval', $this->selectedCompetences),
            'groupes' => array_map('intval', $this->selectedGroupes),
            'etudiants' => array_map('intval', $this->selectedEtudiants),
            'etat' => $this->selectedEtat,
        ];

        return md5((string) json_encode($state));
    }

    private function getCompetencesForCurrentDepartement(): array
    {
        return $this->defaultCompetences;
    }

    private function resolveDepartementForFilters(): ?Departement
    {
        $dept = $this->defaultDepartement;
        if ($dept === null && $this->currentEnseignant !== null) {
            $enseignantDept = $this->enseignantDepartementRepository->findOneBy([
                'enseignant' => $this->currentEnseignant,
                'defaut' => 1,
            ]);
            $dept = $enseignantDept?->getDepartement();
        }

        return $dept;
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
        $this->resetPortfoliosCache();

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
        $this->resetPortfoliosCache();

        $this->selectedSemestreId = $id !== 0 ? $id : null;
        $this->resolveSelectedSemestre();

        $competences = $this->getCompetencesForCurrentDepartement();

        $competencesNiveau = [];

        if ($this->selectedSemestre !== null) {
            $semestre = $this->selectedSemestre;
            $this->groupes = $this->groupeRepository->findBySemestre($semestre);
            $this->etudiants = $this->etudiantRepository->findBySemestre($semestre);

            foreach ($competences as $competence) {
                $niveaux = $this->apcNiveauRepository->findByAnnee($competence, $semestre->getAnnee()->getOrdre());
                $this->appendUniqueEntities($competencesNiveau, $niveaux);
            }
            $this->niveaux = $competencesNiveau;
        } else {
            $this->groupes = [];
            $this->etudiants = [];

            foreach ($this->semestres as $semestre) {
                $groupes = $this->groupeRepository->findBySemestre($semestre);
                $this->appendUniqueEntities($this->groupes, $groupes);

                $etudiants = $this->etudiantRepository->findBySemestre($semestre);
                $this->appendUniqueEntities($this->etudiants, $etudiants);

                foreach ($competences as $competence) {
                    $niveaux = $this->apcNiveauRepository->findByAnnee($competence, $semestre->getAnnee()->getOrdre());
                    $this->appendUniqueEntities($competencesNiveau, $niveaux);
                }
                $this->niveaux = $competencesNiveau;
            }
        }

        if (!empty($this->selectedEtudiants) && !$this->selectedIdsExistInCollection($this->selectedEtudiants, $this->etudiants)) {
            $this->selectedEtudiants = [];
        }

        if (!empty($this->selectedGroupes) && !$this->selectedIdsExistInCollection($this->selectedGroupes, $this->groupes)) {
            $this->selectedGroupes = [];
        }

        if (!empty($this->selectedCompetences) && !$this->selectedIdsExistInCollection($this->selectedCompetences, $this->niveaux)) {
            $this->selectedCompetences = [];
        }

        $this->getDisplayedPortfolios();
    }

    #[LiveAction]
    public function changeCompetences(): void
    {
        $this->currentPage = 1;
        $this->resetPortfoliosCache();
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
        $this->resetPortfoliosCache();
        $this->resolveSelectedSemestre();
        $this->getDisplayedPortfolios();
    }

    #[LiveAction]
    public function changeEtudiants(): void
    {
        $this->currentPage = 1;
        $this->resetPortfoliosCache();
        $this->resolveSelectedSemestre();
        $this->getDisplayedPortfolios();
    }

    public function getTotalPages(): int
    {
        $count = $this->getTotalPortfoliosCount();
        if ($count === 0) {
            return 0;
        }

        return intval(ceil($count / $this->itemsPerPage));
    }

    public function getDisplayedPortfolios(): void
    {
        $this->allPortfolios = $this->getAllPortfolio();
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
        $offset = max(0, ($this->currentPage - 1) * $this->itemsPerPage);
        $cacheKey = $this->getFiltersCacheKey() . ':' . $this->currentPage . ':' . $this->itemsPerPage;
        if ($this->allPortfolioIdsCacheKey === $cacheKey && $this->allPortfolioIdsCache !== null) {
            return $this->allPortfolioIdsCache;
        }

        $dept = $this->resolveDepartementForFilters();
        if ($dept === null) {
            $this->currentPage = 0;
            $this->allPortfolioIdsCacheKey = $cacheKey;
            $this->allPortfolioIdsCache = [];
            return $this->allPortfolioIdsCache;
        }

        $portfolioIds = $this->portfolioRepository->findIdsByFilters(
            $dept,
            $this->selectedSemestre,
            $this->selectedGroupes,
            $this->selectedEtudiants,
            $this->selectedCompetences,
            $this->selectedEtat,
            $this->itemsPerPage,
            $offset
        );

        if ($portfolioIds == null) {
            $this->currentPage = 0;
        }

        $this->allPortfolioIdsCacheKey = $cacheKey;
        $this->allPortfolioIdsCache = $portfolioIds;

        return $this->allPortfolioIdsCache;
    }

    private function getTotalPortfoliosCount(): int
    {
        $this->resolveSelectedSemestre();
        $cacheKey = $this->getFiltersCacheKey();
        if ($this->totalPortfoliosCacheKey === $cacheKey && $this->totalPortfoliosCache !== null) {
            return $this->totalPortfoliosCache;
        }

        $dept = $this->resolveDepartementForFilters();
        if ($dept === null) {
            $this->totalPortfoliosCacheKey = $cacheKey;
            $this->totalPortfoliosCache = 0;
            return 0;
        }

        $this->totalPortfoliosCache = $this->portfolioRepository->countByFilters(
            $dept,
            $this->selectedSemestre,
            $this->selectedGroupes,
            $this->selectedEtudiants,
            $this->selectedCompetences,
            $this->selectedEtat
        );
        $this->totalPortfoliosCacheKey = $cacheKey;

        return $this->totalPortfoliosCache;
    }
}
