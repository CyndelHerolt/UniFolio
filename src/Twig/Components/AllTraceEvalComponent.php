<?php
/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */

namespace App\Twig\Components;

use App\Controller\BaseController;
use App\Entity\ApcNiveau;
use App\Entity\Commentaire;
use App\Entity\Departement;
use App\Entity\Etudiant;
use App\Entity\Groupe;
use App\Entity\Semestre;
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
use App\Classes\DataUserSession;
use Symfony\UX\TwigComponent\Attribute\PostMount;
use Symfony\UX\TwigComponent\Attribute\PreMount;


#[AsLiveComponent('AllTraceEvalComponent')]
final class AllTraceEvalComponent extends BaseController
{
    use DefaultActionTrait;

    public array $semestre = [];
    public array $annee = [];
    public array $dept = [];
    public int $validation = 0;

    public int $itemsPerPage = 4; // nombre d'éléments par page

    #[LiveProp(writable: true)]
    public int $currentPage = 1; // Page actuelle (on commence à 1)

    #[LiveProp(writable: true)]
    /** @var Groupe[] */
    public array $groupes = [];

    #[LiveProp(writable: true)]
    /** @var Etudiant[] */
    public array $etudiants = [];

//    #[LiveProp(writable: true)]
//    public ?int $selectedAnnee = null;

    #[LiveProp(writable: true)]
    public ?Semestre $selectedSemestre = null;

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
        public ValidationRepository  $validationRepository,
        public CommentaireRepository $commentaireRepository,
        #[Required] public Security  $security,
        RequestStack                 $requestStack,
        private FormFactoryInterface $formFactory,
        protected DataUserSession    $dataUserSession,
    )
    {
        $this->requestStack = $requestStack;

        $user = $this->security->getUser()->getEnseignant();
        $dept = $this->departementRepository->findDepartementEnseignantDefaut($user);

        foreach ($dept as $departement) {

            $this->annees = $this->anneeRepository->findByDepartement($departement);

            $this->semestres = $this->semestreRepository->findByDepartementActif($departement);

            // pour chaque semestre, on récupère les groupes
            foreach ($this->semestres as $semestre) {
                $groupes = $this->groupeRepository->findBySemestre($semestre);
                foreach ($groupes as $groupe) {
                    $this->groupes[] = $groupe;
                }
                $this->groupes = array_unique($this->groupes, SORT_REGULAR);

            }
            // pour chaque semestre on récupère les étudiants
            foreach ($this->semestres as $semestre) {
                $etudiants = $this->etudiantRepository->findBySemestre($semestre);
                foreach ($etudiants as $etudiant) {
                    $this->etudiants[] = $etudiant;
                }
            }
            $this->dept[] = $departement;
        }
    }

    #[PostMount]
    public function init()
    {
        $this->changeSemestre($this->selectedSemestre);
//        $this->getDisplayedTraces();

    }

    #[LiveAction]
    public function changeCompetences()
    {
        $this->currentPage = 1;
        $this->etudiants = [];


// Récupérer les niveaux de compétences sélectionnées
        $competences = $this->apcNiveauRepository->findBy(['id' => $this->selectedCompetences]);

        $this->groupes = [];
        foreach ($competences as $competence) {
            // Récupérer l'ordre du niveau de compétence qui est lié à l'année
            $ordre = $competence->getOrdre();

            // Récupérer tous les semestres qui ont un ordre d'année correspondant
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
                // Récupérer les groupes du semestre et les ajouter à l'ensemble de groupes
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
                // supprimer les doublons
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
        if ($this->selectedSemestre !== null) {
            $this->changeSemestre($this->selectedSemestre->getId());
        }
    }

    #[LiveAction]
    public function changeGroupes()
    {
        $this->currentPage = 1;

        // récupérer les étudiants des groupes sélectionnés
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
                        //supprimer les doublons du tableau
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
                    //supprimer les doublons du tableau
                    $competencesNiveau = array_unique($competencesNiveau, SORT_REGULAR);
                }
                $this->niveaux = $competencesNiveau;
            }
        }

        $this->getDisplayedTraces();
        if ($this->selectedSemestre !== null) {
            $this->changeSemestre($this->selectedSemestre->getId());
        }
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
        if ($etudiants == null) {
            foreach ($this->semestres as $semestre) {
                $groupes = $this->groupeRepository->findBySemestre($semestre);
                foreach ($groupes as $groupe) {
                    $this->groupes[] = $groupe;
                }
            }
        }

        $this->getDisplayedTraces();
        if ($this->selectedSemestre !== null) {
            $this->changeSemestre($this->selectedSemestre->getId());
        }
    }

    #[LiveAction]
    public function changeSemestre(#[LiveArg] int $id = null)
    {
        $this->currentPage = 1;
        $user = $this->security->getUser()->getEnseignant();
        $dept = $this->departementRepository->findDepartementEnseignantDefaut($user);
        $referentiel = null;
        foreach ($dept as $departement) {
            $referentiel = $departement->getApcReferentiels()->first();
        }
        $competences = $this->competenceRepository->findBy(['referentiel' => $referentiel]);

        //si un semestre est sélectionné
        if ($id !== null) {
            $this->selectedSemestre = $this->semestreRepository->find($id);
        }

        $competencesNiveau = []; // Reset Competences Niveau array

        // si il y a un semestre sélectionné
        if ($this->selectedSemestre !== null) {
            // on récupère ce semestre
            $semestre = $this->selectedSemestre;

            $this->groupes = $this->groupeRepository->findBySemestre($semestre);
            $this->niveaux = [];
            foreach ($this->groupes as $groupe) {
                if ($groupe->getApcParcours() !== null) {
                    if ($groupe->getApcParcours() === $semestre->getAnnee()->getDiplome()->getApcParcours()) {
                        $parcours = $groupe->getApcParcours();
                        $annee = $semestre->getAnnee();
                        $niveaux = $this->apcNiveauRepository->findByAnneeParcours($annee, $parcours);
                        $competencesNiveau = array_merge($competencesNiveau, $niveaux);
                        $competencesNiveau = array_unique($competencesNiveau, SORT_REGULAR);
                    }
                } else {
                    foreach ($competences as $competence) {
                        $niveaux = $this->apcNiveauRepository->findByAnnee($competence, $semestre->getAnnee()->getOrdre());
                        $competencesNiveau = array_merge($competencesNiveau, $niveaux);
                        //supprimer les doublons du tableau
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
//                    dd($competence);

                    $competencesNiveau = array_merge($competencesNiveau, $niveaux);
                    //supprimer les doublons du tableau
                    $competencesNiveau = array_unique($competencesNiveau, SORT_REGULAR);
                }
            }
            $this->niveaux = $competencesNiveau;
        }

        // si les étudiants sélectionnés sont toujours dans la liste des étudiants
        if ($this->selectedEtudiants !== null) {
            foreach ($this->selectedEtudiants as $selectedEtudiant) {
                $etudiant = $this->etudiantRepository->find($selectedEtudiant);
                if (!in_array($etudiant, $this->etudiants)) {
                    $this->selectedEtudiants = [];
                }
            }
        }
        // si les groupes sélectionnés sont toujours dans la liste des groupes
        if ($this->selectedGroupes !== null) {
            foreach ($this->selectedGroupes as $selectedGroupe) {
                $groupe = $this->groupeRepository->find($selectedGroupe);
                if (!in_array($groupe, $this->groupes)) {
                    $this->selectedGroupes = [];
                }
            }
        }
        // si les compétences sélectionnés sont toujours dans la liste des compétences
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
        $this->allTraces = array_slice($traces, $offset, $this->itemsPerPage);
    }

    // Méthodes d'action pour aller aux pages précédentes/suivantes
    #[LiveAction]
    public function goNextPage()
    {
        if ($this->currentPage < $this->getTotalPages()) {
            $this->currentPage++;
        }
        $this->getDisplayedTraces();
    }

    #[LiveAction]
    public function goPreviousPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
        $this->getDisplayedTraces();
    }

    #[LiveAction]
    public function goToFirstPage()
    {
        $this->currentPage = 1;
        $this->getDisplayedTraces();
    }

    #[LiveAction]
    public function goToLastPage()
    {
        $this->currentPage = $this->getTotalPages();
        $this->getDisplayedTraces();
    }

    public function getAllTrace()
    {

        $dept = $this->dataUserSession->getDepartement();

        $traces = $this->traceRepository->findByFilters($dept, $this->selectedSemestre, $this->selectedCompetences, $this->selectedGroupes, $this->selectedEtudiants);

        if ($traces == null) {
            $this->currentPage = 0;
        }

        return $traces;
    }

}
