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
use App\Entity\Departement;
use App\Entity\Enseignant;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsLiveComponent('AllTraceEvalComponent')]
final class AllTraceEvalComponent extends BaseController
{
    use DefaultActionTrait;

    public array $semestre = [];
    public array $annee = [];
    public array $semestres = [];
    public array $annees = [];
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
    /** @var int[] */
    public array $allTraces = [];

    private RequestStack $requestStack;
    private ?Departement $defaultDepartement = null;
    private ?Enseignant $currentEnseignant = null;
    private array $defaultCompetences = [];
    private ?array $allTraceIdsCache = null;
    private ?string $allTraceIdsCacheKey = null;
    private ?int $totalTracesCache = null;
    private ?string $totalTracesCacheKey = null;

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

    private function resetTracesCache(): void
    {
        $this->allTraceIdsCache = null;
        $this->allTraceIdsCacheKey = null;
        $this->totalTracesCache = null;
        $this->totalTracesCacheKey = null;
    }

    private function getFiltersCacheKey(): string
    {
        $semestreId = $this->selectedSemestre?->getId();

        $state = [
            'dept' => $this->defaultDepartement?->getId(),
            'semestre' => $semestreId,
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
        $this->resetTracesCache();
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
        $this->resetTracesCache();
        $this->resolveSelectedSemestre();

        $competences = $this->apcNiveauRepository->findBy(['id' => $this->selectedCompetences]);

        $this->groupes = [];
        foreach ($competences as $competence) {
            $ordre = $competence->getOrdre();
            $semestres = $this->semestres;

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
                $this->appendUniqueEntities($this->groupes, $groupes);
            }

            foreach ($this->groupes as $groupe) {
                $etudiants = $groupe->getEtudiants()->toArray();
                $this->appendUniqueEntities($this->etudiants, $etudiants);
            }
        }

        if ($competences == null) {
            foreach ($this->semestres as $semestre) {
                $etudiants = $this->etudiantRepository->findBySemestre($semestre);
                $this->appendUniqueEntities($this->etudiants, $etudiants);
                $groupes = $this->groupeRepository->findBySemestre($semestre);
                $this->appendUniqueEntities($this->groupes, $groupes);
            }
        }

        $this->getDisplayedTraces();
    }

    #[LiveAction]
    public function changeGroupes()
    {
        $this->currentPage = 1;
        $this->resetTracesCache();
        $this->resolveSelectedSemestre();

        $groupes = $this->groupeRepository->findBy(['id' => $this->selectedGroupes]);

        $this->etudiants = [];
        $this->niveaux = [];
        $competencesNiveau = [];
        $competences = $this->getCompetencesForCurrentDepartement();

        foreach ($groupes as $groupe) {
            $this->appendUniqueEntities($this->etudiants, $groupe->getEtudiants()->toArray());

            $semestres = $groupe->getTypeGroupe()->getSemestre();
            foreach ($semestres as $semestre) {
                if ($groupe->getApcParcours() !== null) {
                    if ($semestre->getAnnee()->getDiplome()->getApcParcours() == $groupe->getApcParcours()) {
                        $annee = $semestre->getAnnee();
                        $parcours = $groupe->getApcParcours();
                        $niveaux = $this->apcNiveauRepository->findByAnneeParcours($annee, $parcours);
                        $this->appendUniqueEntities($competencesNiveau, $niveaux);
                    }
                } else {
                    foreach ($competences as $competence) {
                        $niveaux = $this->apcNiveauRepository->findByAnnee($competence, $semestre->getAnnee()->getOrdre());
                        $this->appendUniqueEntities($competencesNiveau, $niveaux);
                    }
                }
                $this->niveaux = $competencesNiveau;
            }
        }

        if ($groupes == null) {
            foreach ($this->semestres as $semestre) {
                $etudiants = $this->etudiantRepository->findBySemestre($semestre);
                $this->appendUniqueEntities($this->etudiants, $etudiants);
                foreach ($competences as $competence) {
                    $niveaux = $this->apcNiveauRepository->findByAnnee($competence, $semestre->getAnnee()->getOrdre());
                    $this->appendUniqueEntities($competencesNiveau, $niveaux);
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
        $this->resetTracesCache();
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
                $this->appendUniqueEntities($this->groupes, $groupes);
            }
        }

        $this->groupes = $this->uniqueEntities($this->groupes);

        $this->getDisplayedTraces();
    }

    #[LiveAction]
    public function changeSemestre(#[LiveArg] int $id = 0)
    {
        $this->currentPage = 1;
        $this->resetTracesCache();

        if ($id !== null && $id !== 0) {
            $this->selectedSemestreId = $id;
            $this->selectedSemestre = $this->semestreRepository->find($id);
        } else {
            $this->selectedSemestreId = null;
            $this->selectedSemestre = null;
        }

        $competences = $this->getCompetencesForCurrentDepartement();
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
                        $this->appendUniqueEntities($competencesNiveau, $niveaux);
                    }
                }
                $this->niveaux = $competencesNiveau;
            }

            $this->etudiants = $this->etudiantRepository->findBySemestre($semestre);
        } else {
            foreach ($this->semestres as $semestre) {
                foreach ($competences as $competence) {
                    $niveaux = $this->apcNiveauRepository->findByAnnee($competence, $semestre->getAnnee()->getOrdre());
                    $this->appendUniqueEntities($competencesNiveau, $niveaux);
                }
            }
            $this->niveaux = $competencesNiveau;
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

        $this->getDisplayedTraces();
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

    public function getTotalPages()
    {
        $count = $this->getTotalTracesCount();
        if ($count === 0) {
            return 0;
        }

        return intval(ceil($count / $this->itemsPerPage));
    }

    public function getDisplayedTraces()
    {
        $this->allTraces = $this->getAllTrace();
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
        $offset = max(0, ($this->currentPage - 1) * $this->itemsPerPage);
        $cacheKey = $this->getFiltersCacheKey() . ':' . $this->currentPage . ':' . $this->itemsPerPage;
        if ($this->allTraceIdsCacheKey === $cacheKey && $this->allTraceIdsCache !== null) {
            return $this->allTraceIdsCache;
        }

        $dept = $this->resolveDepartementForFilters();

        if ($dept === null) {
            $this->currentPage = 0;
            $this->allTraceIdsCacheKey = $cacheKey;
            $this->allTraceIdsCache = [];
            return $this->allTraceIdsCache;
        }

        $traceIds = $this->traceRepository->findIdsByFilters(
            $dept,
            $this->selectedSemestre,
            $this->selectedCompetences,
            $this->selectedGroupes,
            $this->selectedEtudiants,
            $this->selectedEtat,
            $this->itemsPerPage,
            $offset
        );

        if ($traceIds == null) {
            $this->currentPage = 0;
        }

        $this->allTraceIdsCacheKey = $cacheKey;
        $this->allTraceIdsCache = $traceIds;

        return $this->allTraceIdsCache;
    }

    private function getTotalTracesCount(): int
    {
        $this->resolveSelectedSemestre();
        $cacheKey = $this->getFiltersCacheKey();
        if ($this->totalTracesCacheKey === $cacheKey && $this->totalTracesCache !== null) {
            return $this->totalTracesCache;
        }

        $dept = $this->resolveDepartementForFilters();
        if ($dept === null) {
            $this->totalTracesCacheKey = $cacheKey;
            $this->totalTracesCache = 0;
            return 0;
        }

        $this->totalTracesCache = $this->traceRepository->countByFilters(
            $dept,
            $this->selectedSemestre,
            $this->selectedCompetences,
            $this->selectedGroupes,
            $this->selectedEtudiants,
            $this->selectedEtat
        );
        $this->totalTracesCacheKey = $cacheKey;

        return $this->totalTracesCache;
    }
}
