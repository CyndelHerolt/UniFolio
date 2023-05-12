<?php

namespace App\Classes;

use App\Entity\Annee;
use App\Entity\Departement;
use App\Entity\Diplome;
use App\Entity\Semestre;
use App\Repository\AnneeRepository;
use App\Repository\AnneeUniversitaireRepository;
use App\Repository\DepartementRepository;
use App\Repository\DiplomeRepository;
use App\Repository\EnseignantRepository;
use App\Repository\EtudiantRepository;
use App\Repository\GroupeRepository;
use App\Repository\SemestreRepository;
use App\Repository\TypeGroupeRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
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


    /**
     * DataUserSession constructor.
     *
     * @throws NonUniqueResultException
     */
    public function __construct(
        protected SemestreRepository $semestreRepository,
        protected AnneeRepository $anneeRepository,
        protected DiplomeRepository $diplomeRepository,
        protected EnseignantRepository $enseignantRepository,
        protected EtudiantRepository $etudiantRepository,
        protected DepartementRepository $departementRepository,
        protected GroupeRepository $groupeRepository,
        protected TypeGroupeRepository $typeGroupeRepository,
        protected TokenStorageInterface $user,
        protected Security $security,
        EventDispatcherInterface $eventDispatcher,
        RequestStack $session
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
        } elseif ($etudiant) {
            $this->departement = $this->departementRepository->findOneBy(['id' => $session->get('departement')]);
        } else {
            throw new AccessDeniedException('Vous n\'avez pas accès à cette page');
        }

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

    public function getAllEnseignants() {
        return $this->enseignantRepository->findByDepartement($this->departement);
    }

    public function getEtudiantsDept() {
        return $this->etudiantRepository->findByDepartementArray($this->departement);
    }

    public function getEtudiantsSemestre() {
        foreach ($this->semestresActifs as $semestre) {
            $etudiants[] = $this->etudiantRepository->findBySemestre($semestre);
        }
        return $etudiants;
    }

    public function getEtudiantAnnee() {
        foreach ($this->annees as $annee) {
            $etudiants[] = $this->etudiantRepository->findByAnnee($annee);
        }
        return $etudiants;
    }

    public function getTypesGroupe() {
        return $this->typesGroupes;
    }

    public function getGroupes() {
        return $this->groupeRepository->findByDepartementSemestreActif($this->departement);
    }

    public function DepartementDefaut() {
        return $this->departementRepository->findDepartementEnseignantDefaut($this->enseignant);
    }

}
