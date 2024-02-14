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
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\PostMount;
use function PHPUnit\Framework\isEmpty;

#[AsLiveComponent('AllEtudiantComponent')]
class AllEtudiantComponent extends BaseController
{
    use DefaultActionTrait;

    public array $semestre = [];
    public array $annee = [];
    public array $dept = [];
    public int $validation = 0;

    public int $itemsPerPage = 25; // nombre d'éléments par page

    #[LiveProp(writable: true)]
    public int $currentPage = 1; // Page actuelle (on commence à 1)

    #[LiveProp(writable: true)]
    /** @var Groupe[] */
    public array $groupes = [];

    #[LiveProp(writable: true)]
    /** @var Etudiant[] */
    public array $etudiants = [];

    #[LiveProp(writable: true)]
    public array $etudiantsNonInscrits = [];

    #[LiveProp(writable: true)]
    public array $selectedEtudiantsNonInscrits = [];

    #[LiveProp(writable: true)]
    public bool $selectAll = false;

    #[LiveProp(writable: true)]
    public bool $successMessage = false;

    #[LiveProp(writable: true)]
    public ?Semestre $selectedSemestre = null;

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
    /** @var Etudiant[] */
    public array $allEtudiants = [];

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
        HttpClientInterface                       $client,
        MailerInterface                           $mailer,
    )
    {
        $this->client = $client;
        $this->mailer = $mailer;
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
    }

    #[LiveAction]
    public function changeEtat(#[LiveArg] int $id = 0)
    {
        $this->currentPage = 1;
        $this->allEtudiants = [];

        switch ($id) {
            case 0: // Toutes les traces
                $this->selectedEtat = 0;
                break;
            case 1: // Traces évaluées
                $this->selectedEtat = 1;
                break;
            case 2: // Traces non évaluées
                $this->selectedEtat = 2;
                break;
            case 3: // Pas de portfolios
                $this->selectedEtat = 3;
                break;
        }

        $this->getDisplayedEtudiants();
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
                        $competencesNiveau = $niveaux;
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

        $this->getDisplayedEtudiants();
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

        $this->getDisplayedEtudiants();
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

        $this->getDisplayedEtudiants();
        if ($this->selectedSemestre !== null) {
            $this->changeSemestre($this->selectedSemestre->getId());
        }
    }

    public function getDisplayedEtudiants()
    {
        $offset = ($this->currentPage - 1) * $this->itemsPerPage;
        $portfolios = $this->getAllEtudiant();
        $this->allEtudiants = array_slice($portfolios, $offset, $this->itemsPerPage);
    }

    // Méthode pour obtenir le nombre total de pages
    public function getTotalPages()
    {
        $count = count($this->getAllEtudiant());
        return intval(ceil($count / $this->itemsPerPage));
    }

    // Méthodes d'action pour aller aux pages précédentes/suivantes
    #[LiveAction]
    public function goNextPage()
    {
        if ($this->currentPage < $this->getTotalPages()) {
            $this->currentPage++;
        }
        $this->getDisplayedEtudiants();
    }

    #[LiveAction]
    public function goPreviousPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
        $this->getDisplayedEtudiants();
    }

    #[LiveAction]
    public function goToFirstPage()
    {
        $this->currentPage = 1;
        $this->getDisplayedEtudiants();
    }

    #[LiveAction]
    public function goToLastPage()
    {
        $this->currentPage = $this->getTotalPages();
        $this->getDisplayedEtudiants();
    }

    #[LiveAction]
    public function verifInscrits()
    {
        $this->etudiantsNonInscrits = [];

        if ($this->selectedSemestre !== null) {
            $semestres = $this->semestreRepository->findBy(['id' => $this->selectedSemestre->getId()]);
        } else {
            $semestres = $this->semestreRepository->findBy(['id' => $this->semestres]);
        }
        foreach ($semestres as $semestre) {

            $response = $this->client->request(
                'GET',
                $_ENV['API_URL'] . 'unifolio/etudiant',
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'x-api-key' => $_ENV['API_KEY']
                    ],
                    'query' => [
                        'semestre' => $semestre->getId(),
                    ]
                ]
            );
            $response = $response->toArray();

            foreach ($response as $etudiantAPI) {
                $etudiant = $this->etudiantRepository->findOneBy(['username' => $etudiantAPI['username']]);
                if (!$etudiant) {
                    $this->etudiantsNonInscrits[] = $etudiantAPI;
                }
            }

        }
    }

    #[LiveAction]
    public function changeEtudiantsNonInscrits(#[LiveArg] $id = null)
    {
        if ($id !== null) {
            // Si l'étudiant est déjà dans selectedEtudiantsNonInscrits, on le supprime
            if (($key = array_search($id, $this->selectedEtudiantsNonInscrits)) !== false) {
                unset($this->selectedEtudiantsNonInscrits[$key]);
            } else {
                // Sinon, on l'ajoute au tableau selectedEtudiantsNonInscrits
                $this->selectedEtudiantsNonInscrits[] = $id;
            }
        }
    }

    #[LiveAction]
    public function changeSelectAll()
    {
        // Si selectAll est vrai, nous voulons ajouter tous les étudiants à selectedEtudiantsNonInscrits
        if ($this->selectAll) {
            $this->selectedEtudiantsNonInscrits = array_map(fn($etudiant) => $etudiant['mail_univ'], $this->etudiantsNonInscrits);
        } else {
            // Sinon, nous voulons désélectionner tous les étudiants
            $this->selectedEtudiantsNonInscrits = [];
        }
    }

    #[LiveAction]
    public function sendMailNonInscrits()
    {
        if (!empty($this->selectedEtudiantsNonInscrits)) {
            foreach ($this->selectedEtudiantsNonInscrits as $etudiant) {
                $email = (new TemplatedEmail())
                    ->from(new Address('portfolio.iut-troyes@univ-reims.fr', 'UniFolio Mail Bot'))
                    ->to($etudiant)
                    ->subject('UniFolio - Vous n\'avez toujours pas de compte')
                    ->htmlTemplate('email.html.twig')
                    ->context([
                        'user' => null,
                        'email_subject' => 'Rappel - Création de votre compte',
                        'email_message' => '<p>Nous tenons à vous rappeler l\'importance de créer votre compte sur UniFolio, notre plateforme en ligne dédiée à la constitution de votre portfolio universitaire.</p>
                        <p>Pour commencer, il vous suffit de suivre ces étapes simples :</p>
                        <ul>
                            <li>Accédez à <a href="https://portfolio.iut-troyes.univ-reims.fr/users/inscription">UniFolio</a>.</li>
                            <li>Renseignez votre login URCA et un mot de passe de votre choix.</li>
                            <li>Validez votre compte en cliquant sur le bouton dans le mail de confirmation.</li>
                        </ul>
                        <p>N\'oubliez pas que votre portfolio est soumis à évaluation et par conséquent indispensable à la validation de vos compétences.</p>
                        <p>En cas de questions ou de difficultés, n\'hésitez pas à contacter notre équipe de support à portfolio.iut-troyes@univ-reims.fr.</p>
                        <p>Nous comptons sur votre participation active et vous remercions par avance pour votre implication dans la création de votre portfolio universitaire.</p>
                        <p>Cordialement,</p>',

                        'email_button' => 'inscription'
                    ]);
                $this->mailer->send($email);
            }
            $this->successMessage = true;
            $this->selectedEtudiantsNonInscrits = [];
        } else {
            $this->successMessage = false;
        }
    }

    public function getAllEtudiant()
    {
//        $dept = $this->dataUserSession->getDepartement();
        $user = $this->getUser();
        $enseignant = $user->getEnseignant();

        $dept = $this->enseignantDepartementRepository->findOneBy(['enseignant' => $enseignant, 'defaut' => 1]);

        $etudiants = $this->etudiantRepository->findByFilters($dept, $this->selectedSemestre, $this->selectedGroupes, $this->selectedEtudiants, $this->selectedCompetences, $this->selectedEtat);

        if ($etudiants === null) {
            $this->currentPage = 0;
        }

        return $etudiants;
    }
}
