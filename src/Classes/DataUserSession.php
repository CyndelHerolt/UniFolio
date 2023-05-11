<?php

namespace App\Classes;

use App\Entity\Annee;
use App\Entity\AnneeUniversitaire;
use App\Entity\Departement;
use App\Entity\Diplome;
use App\Entity\Enseignant;
use App\Entity\EnseignantDepartement;
use App\Entity\Etudiant;
use App\Entity\Semestre;
use App\Repository\AnneeRepository;
use App\Repository\AnneeUniversitaireRepository;
use App\Repository\DepartementRepository;
use App\Repository\DiplomeRepository;
use App\Repository\EnseignantRepository;
use App\Repository\EtudiantRepository;
use App\Repository\SemestreRepository;
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

        if ($enseignant) {
            $this->departement = $this->departementRepository->findOneBy(['id' => $session->get('departement')]);
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

    public function getSemestres(): array
    {
        return $this->semestres;
    }

    public function getSemestresActifs(): array
    {
        return $this->semestresActifs;
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
//        dd($this->etudiantRepository->findByDepartementArray($this->departement));
        return $this->etudiantRepository->findByDepartementArray($this->departement);
    }
}
