<?php

namespace App\Classes;

use App\Entity\Annee;
use App\Entity\Departement;
use App\Entity\Diplome;
use App\Entity\Etudiant;
use App\Entity\Groupe;
use App\Entity\Semestre;
use App\Repository\AnneeRepository;
use App\Repository\AnneeUniversitaireRepository;
use App\Repository\DepartementRepository;
use App\Repository\DiplomeRepository;
use App\Repository\EnseignantDepartementRepository;
use App\Repository\EnseignantRepository;
use App\Repository\EtudiantRepository;
use App\Repository\GroupeRepository;
use App\Repository\NotificationRepository;
use App\Repository\SemestreRepository;
use App\Repository\TypeGroupeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use \Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

// Récupère les données d'un session utilisateur
class DataUserSession
{

    /**
     * @var Semestre[]
     */
    protected array $semestres = []; // semestres actifs de la departement

    /**
     * @var Diplome[]
     */
    protected array $diplomes = [];

    /**
     * @var Annee[]
     */
    protected array $annees = [];

    protected ?Departement $departement = null;

    protected AnneeUniversitaireRepository $anneeUniversitaireRepository;

    private array $semestresActifs = [];

    private $notifications;

    /**
     * DataUserSession constructor.
     *
     * @throws NonUniqueResultException
     */
    public function __construct(
        protected SemestreRepository              $semestreRepository,
        protected AnneeRepository                 $anneeRepository,
        protected DiplomeRepository               $diplomeRepository,
        protected EnseignantRepository            $enseignantRepository,
        protected EtudiantRepository              $etudiantRepository,
        protected DepartementRepository           $departementRepository,
        protected EnseignantDepartementRepository $enseignantDepartementRepository,
        protected GroupeRepository                $groupeRepository,
        protected TypeGroupeRepository            $typeGroupeRepository,
        protected TokenStorageInterface           $user,
        protected Security                        $security,
        EntityManagerInterface                    $entityManager,
        EventDispatcherInterface                  $eventDispatcher,
        RequestStack                              $session,
        protected NotificationRepository          $notificationRepository,
    )
    {
        $this->requestStack = $session;
        $session = $this->requestStack->getSession();

        $enseignant = $this->enseignantRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $etudiant = $this->etudiantRepository->findOneBy(['username' => $this->getUser()->getUsername()]);

        $this->enseignant = $enseignant;
        $this->etudiant = $etudiant;

        if ($enseignant) {
            $this->departement = $this->departementRepository->findOneBy(['id' => $session->get('departement')]);
            $this->allDepartements = $this->departementRepository->findDepartementEnseignant($enseignant);
            if (null !== $this->departement) {
                $this->semestres = $this->semestreRepository->findByDepartement($this->departement);
                $this->semestresActifs = [];
                foreach ($this->semestres as $semestre) {
                    if ($semestre->isActif()) {
                        $this->semestresActifs[] = $semestre;
                    }
                }
                $this->diplomes = $this->diplomeRepository->findByDepartement($this->departement);
                $this->annees = $this->anneeRepository->findByDepartement($this->departement);
                $this->typesGroupes = $this->typeGroupeRepository->findByDepartementSemestresActifs($this->departement);
            }
        } elseif ($etudiant) {
            $this->departement = $this->departementRepository->findOneBy(['id' => $session->get('departement')]);
            // TODO: récupérer seulement les données propres à l'étudiant
            $this->groupes = $this->groupeRepository->findGroupesEtudiant($etudiant);
            $this->typesGroupes = $this->typeGroupeRepository->findTypesGroupesEtudiant($etudiant);
        } else {
            throw new AccessDeniedException('Vous n\'avez pas accès à cette page');
        }


        //Si l'utilisateur connecté a les roles admin et enseignant, on set son département defaut à true dans la table enseignant_departement
        if ($this->security->isGranted('ROLE_TEST') && $this->security->isGranted('ROLE_ENSEIGNANT')) {
            $departement = $this->departementRepository->findOneBy(['libelle' => 'MMI']);
            $departementDefaut = $enseignantDepartementRepository->findOneBy(['enseignant' => $enseignant, 'departement' => $departement]);
            $departementDefaut->setDefaut(true);
            if (null !== $departement) {
                $semestres = $this->semestreRepository->findByDepartement($departement);
                $this->semestresActifs = [];
                foreach ($semestres as $semestre) {
                    if ($semestre->isActif()) {
                        $this->semestresActifs[] = $semestre;
                    }
                }
                $this->diplomes = $this->diplomeRepository->findByDepartement($departement);
                $this->annees = $this->anneeRepository->findByDepartement($departement);
                $this->typesGroupes = $this->typeGroupeRepository->findByDepartementSemestresActifs($departement);
            }
            $entityManager->flush();
        } elseif ($this->security->isGranted('ROLE_TEST') && $this->security->isGranted('ROLE_ETUDIANT')) {

            $entityManager->flush();
        }
    }

    public function getUser(): ?UserInterface
    {
        if (null !== $this->user->getToken()) {
            return $this->user->getToken()->getUser();
        }

        return null;
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(Departement $departement): void
    {
        $this->departement = $departement;
    }

    public function getAllDepartementsEnseignant(): array
    {
        return $this->allDepartements;
    }

    public function getSemestres(): array
    {
        return $this->semestres;
    }

    public function getSemestresActifs(): array
    {
        return $this->semestresActifs;
    }

    public function getSemestresByAnnee(Annee $annee, int $ordre): array
    {
        $semestres = [];
        foreach ($this->semestres as $semestre) {
            if ($semestre->getAnnee() === $annee && $annee->getOrdre() === $ordre) {
                $semestres[] = $semestre;
            }
        }
        return $semestres;
    }

    public function getDiplomes(): array
    {
        return $this->diplomes;
    }

    public function getAnnees(): array
    {
        return $this->annees;
    }

    public function getAllEnseignants()
    {
        return $this->enseignantRepository->findByDepartement($this->departement);
    }

    public function getEtudiantsDept()
    {
        return $this->etudiantRepository->findByDepartementArray($this->departement);
    }

    public function getEtudiantsSemestre()
    {
        foreach ($this->semestresActifs as $semestre) {
            $etudiants[] = $this->etudiantRepository->findBySemestre($semestre);
        }
        return $etudiants;
    }

    public function getEtudiantAnnee()
    {
        foreach ($this->annees as $annee) {
            $etudiants[] = $this->etudiantRepository->findByAnnee($annee);
        }
        return $etudiants;
    }

    public function getTypesGroupe()
    {
        return $this->typesGroupes;
    }

    public function getGroupes()
    {
        return $this->groupeRepository->findByDepartementSemestreActif($this->departement);
    }

    public function DepartementDefaut()
    {
        return $this->departementRepository->findDepartementEnseignantDefaut($this->enseignant);
    }

    public function getNotifications()
    {

        if ($this->security->isGranted('ROLE_ENSEIGNANT')) {
            $notifications = $this->notificationRepository->findBy(
                ['enseignant' => $this->getUser()->getEnseignant()],
                ['dateCreation' => 'DESC']
            );
        } elseif ($this->security->isGranted('ROLE_ETUDIANT')) {
            $notifications = $this->notificationRepository->findBy(
                ['etudiant' => $this->getUser()->getEtudiant()],
                ['dateCreation' => 'DESC']
            );
        }

        return $notifications;
    }

}
