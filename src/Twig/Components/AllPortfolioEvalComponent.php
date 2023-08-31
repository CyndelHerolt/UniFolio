<?php

namespace App\Twig\Components;

use App\Classes\DataUserSession;
use App\Controller\BaseController;

use App\Entity\ApcNiveau;
use App\Entity\Etudiant;
use App\Entity\Groupe;
use App\Entity\Portfolio;
use App\Repository\AnneeRepository;
use App\Repository\ApcNiveauRepository;
use App\Repository\CommentaireRepository;
use App\Repository\CompetenceRepository;
use App\Repository\DepartementRepository;
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

#[AsLiveComponent('AllPortfolioEvalComponent')]
final class AllPortfolioEvalComponent extends BaseController
{
    use DefaultActionTrait;

    public int $itemsPerPage = 4; // nombre d'éléments par page

    #[LiveProp(writable: true)]
    public int $currentPage = 1; // Page actuelle (on commence à 1)

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
    public ?string $selectedValidation = '';

    #[LiveProp(writable: true)]
    /** @var ApcNiveau[] */
    public array $niveaux = [];

    #[LiveProp(writable: true)]
    /** @var Portfolio[] */
    public array $allPortfolios = [];

    public function __construct(
        protected PortfolioRepository   $portfolioRepository,
        protected DepartementRepository $departementRepository,
        protected ApcNiveauRepository   $apcNiveauRepository,
        protected EtudiantRepository    $etudiantRepository,
        protected GroupeRepository      $groupeRepository,
        protected SemestreRepository    $semestreRepository,
        protected AnneeRepository       $anneeRepository,
        protected TypeGroupeRepository  $typeGroupeRepository,
        protected ValidationRepository  $validationRepository,
        protected CommentaireRepository $commentaireRepository,
        protected CompetenceRepository  $competenceRepository,
        protected OrdreTraceRepository  $ordreTraceRepository,
        protected OrdrePageRepository   $ordrePageRepository,
        protected TraceRepository       $traceRepository,
        #[Required] public Security     $security,
        RequestStack                    $requestStack,
        private FormFactoryInterface    $formFactory,
        protected DataUserSession       $dataUserSession,
    )
    {
        $this->requestStack = $requestStack;
        $this->allPortfolios = $this->getAllPortfolio();

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
    public function changeAnnee(#[LiveArg] int $id = null): void
    {
        $this->currentPage = 1;
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
            if ($this->selectedEtudiants == null) {
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
            }
        } else {
            if ($this->selectedEtudiants == null) {
                $dept = $this->departementRepository->findOneBy(['id' => $this->dept]);
                $groupes = $this->groupeRepository->findByDepartementSemestreActif($dept);
                $this->groupes = $groupes;
            }
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
                $dept = $this->departementRepository->findOneBy(['id' => $this->dept]);
                $etudiants = $this->etudiantRepository->findByDepartement($dept);
                $this->etudiants = $etudiants;
            }
        }

        $this->allPortfolios = $this->getAllPortfolio();
    }

    #[LiveAction]
    public function changeCompetences()
    {
        $this->currentPage = 1;
        $this->allPortfolios = $this->getAllPortfolio();
        $this->changeAnnee($this->selectedAnnee);
    }

    #[LiveAction]
    public function changeEtudiants()
    {
        $this->currentPage = 1;
        // récupérer les groupes des étudiants sélectionnés
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

        $this->allPortfolios = $this->getAllPortfolio();
        $this->changeAnnee($this->selectedAnnee);
    }

    #[LiveAction]
    public function changeGroupes()
    {
        $this->currentPage = 1;
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
        $this->allPortfolios = $this->getAllPortfolio();
        $this->changeAnnee($this->selectedAnnee);
    }


    // Méthode pour obtenir le nombre total de pages
    public function getTotalPages()
    {
        $count = count($this->getAllPortfolio());
        return intval(ceil($count / $this->itemsPerPage));
    }

    // Nouvelle méthode pour obtenir une partie des traces basée sur la page actuelle
    public function getDisplayedPortfolios()
    {
        $offset = ($this->currentPage - 1) * $this->itemsPerPage;
        $traces = $this->getAllPortfolio();
        return array_slice($traces, $offset, $this->itemsPerPage);
    }

    // Méthodes d'action pour aller aux pages précédentes/suivantes
    #[LiveAction]
    public function goNextPage()
    {
        if ($this->currentPage < $this->getTotalPages()) {
            $this->currentPage++;
        }

    }

    #[LiveAction]
    public function goPreviousPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }

    #[LiveAction]
    public function goToFirstPage()
    {
        $this->currentPage = 1;
    }

    #[LiveAction]
    public function goToLastPage()
    {
        $this->currentPage = $this->getTotalPages();
    }


    public function getAllPortfolio()
    {
        $dept = $this->dataUserSession->getDepartement();

        $portfolios = $this->portfolioRepository->findByFilters($dept, $this->selectedAnnee, $this->selectedGroupes, $this->selectedEtudiants, $this->selectedCompetences);

        return $portfolios;
    }
}
