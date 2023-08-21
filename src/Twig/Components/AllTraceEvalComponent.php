<?php

namespace App\Twig\Components;

use App\Controller\BaseController;
use App\Entity\ApcNiveau;
use App\Entity\Commentaire;
use App\Entity\Etudiant;
use App\Entity\Groupe;
use App\Entity\Trace;
use App\Form\CommentaireType;
use App\Repository\AnneeRepository;
use App\Repository\ApcNiveauRepository;
use App\Repository\CommentaireRepository;
use App\Repository\CompetenceRepository;
use App\Repository\DepartementRepository;
use App\Repository\EtudiantRepository;
use App\Repository\GroupeRepository;
use App\Repository\SemestreRepository;
use App\Repository\TraceRepository;
use App\Repository\TypeGroupeRepository;
use App\Repository\ValidationRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
    public int $validation = 0;

    public int $itemsPerPage = 5; // nombre d'éléments par page

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
        public ValidationRepository  $validationRepository,
        public CommentaireRepository $commentaireRepository,
        #[Required] public Security  $security,
        RequestStack                 $requestStack,
        private FormFactoryInterface $formFactory,
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
        $this->currentPage = 1;
        $this->allTraces = $this->getAllTrace();
        $this->changeAnnee($this->selectedAnnee);
    }

    #[LiveAction]
    public function changeValidation()
    {
        $this->currentPage = 1;
        $selectedValidation = $this->selectedValidation;
        list($validationId, $state) = explode('-', $selectedValidation);
        $validationId = intval($validationId);
        $state = intval($state);

        $validation = $this->validationRepository->find($validationId);

        $validation->setEtat($state);
        $validation->setEnseignant($this->security->getUser()->getEnseignant());
        $validation->setDateCreation(new \DateTime());
        if ($validation->getDateCreation() != null) {
            $validation->setDateModification(new \DateTime());
        }
        $this->validationRepository->save($validation, true);

        $this->allTraces = $this->getAllTrace();
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

        $this->allTraces = $this->getAllTrace();
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

        $this->allTraces = $this->getAllTrace();
        $this->changeAnnee($this->selectedAnnee);
    }

    #[LiveAction]
    public function changeAnnee(#[LiveArg] int $id = null)
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

        $this->allTraces = $this->getAllTrace();
    }

    // Méthode pour obtenir le nombre total de pages
    public function getTotalPages()
    {
        $count = count($this->getAllTrace());
        return intval(ceil($count / $this->itemsPerPage));
    }

    // Nouvelle méthode pour obtenir une partie des traces basée sur la page actuelle
    public function getDisplayedTraces()
    {
        $offset = ($this->currentPage - 1) * $this->itemsPerPage;
        $traces = $this->getAllTrace();
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

    public function getAllTrace()
    {

        $traces = $this->traceRepository->findByFilters($this->selectedAnnee, $this->selectedCompetences, $this->selectedGroupes, $this->selectedEtudiants);

        if ($traces == null) {
            $this->currentPage = 0;
        }

        return $traces;
    }

}
