<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Entity;

use App\Repository\EtudiantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
class Etudiant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    private ?Departement $departement = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mail_perso = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mail_univ = null;

    #[ORM\ManyToMany(targetEntity: Groupe::class, inversedBy: 'etudiants', cascade: ['persist', 'remove'])]
    private Collection $groupe;

    #[ORM\OneToMany(mappedBy: 'etudiant', targetEntity: Cv::class)]
    private Collection $cvs;

    #[ORM\ManyToOne(inversedBy: 'etudiants')]
    private ?AnneeUniversitaire $annee_universitaire = null;

    #[ORM\OneToMany(mappedBy: 'etudiant', targetEntity: Bibliotheque::class, cascade: ['persist', 'remove'])]
    private Collection $bibliotheques;

    #[ORM\OneToMany(mappedBy: 'etudiant', targetEntity: Portfolio::class)]
    private Collection $portfolios;

    #[ORM\OneToOne(mappedBy: 'etudiant', cascade: ['persist', 'remove'])]
    private ?Users $users = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $bio = null;

    #[ORM\Column(length: 75, nullable: true)]
    private ?string $username = null;

    #[ORM\ManyToOne(targetEntity: Semestre::class, inversedBy: 'etudiants')]
    private ?Semestre $semestre = null;

    #[ORM\OneToMany(mappedBy: 'etudiant', targetEntity: Notification::class)]
    private ?Collection $notifications;

    #[ORM\Column]
    private ?bool $opt_alternance = false;

    #[ORM\Column]
    private ?bool $opt_stage = false;

    #[ORM\Column(nullable: true)]
    private ?int $annee_sortie = null;

    public function __construct()
    {
        $this->groupe = new ArrayCollection();
        $this->cvs = new ArrayCollection();
        $this->bibliotheques = new ArrayCollection();
        $this->portfolios = new ArrayCollection();
        $this->notifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getMailPerso(): ?string
    {
        return $this->mail_perso;
    }

    public function setMailPerso(?string $mail_perso): self
    {
        $this->mail_perso = $mail_perso;

        return $this;
    }

    public function getMailUniv(): ?string
    {
        return $this->mail_univ;
    }

    public function setMailUniv(?string $mail_univ): self
    {
        $this->mail_univ = $mail_univ;

        return $this;
    }

    /**
     * @return Collection<int, Groupe>
     */
    public function getGroupe(): ?Collection
    {
        return $this->groupe;
    }

    // méthode pour récupérer les libellées des groupes de l'étudiant sous forme de string
    public function getGroupesAsString(): ?string
    {
        return implode(', ', $this->groupe->map(function ($groupe) {
            return $groupe->getLibelle();
        })->toArray());
    }

    public function getGroupeId(): ?array
    {
        $groupes = [];
        foreach ($this->groupe as $groupe) {
            $groupes[] = $groupe->getId();
        }
        return $groupes;
    }

    public function setGroupeId($groupes)
    {
    }

    public function addGroupe(?Groupe $groupe): self
    {
        if (!$this->groupe->contains($groupe)) {
            $this->groupe->add($groupe);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        $this->groupe->removeElement($groupe);

        return $this;
    }

    /**
     * @return Collection<int, Cv>
     */
    public function getCvs(): Collection
    {
        return $this->cvs;
    }

    public function addCv(Cv $cv): self
    {
        if (!$this->cvs->contains($cv)) {
            $this->cvs->add($cv);
            $cv->setEtudiant($this);
        }

        return $this;
    }

    public function removeCv(Cv $cv): self
    {
        if ($this->cvs->removeElement($cv)) {
            // set the owning side to null (unless already changed)
            if ($cv->getEtudiant() === $this) {
                $cv->setEtudiant(null);
            }
        }

        return $this;
    }

    public function getAnneeUniversitaire(): ?AnneeUniversitaire
    {
        return $this->annee_universitaire;
    }

    public function setAnneeUniversitaire(?AnneeUniversitaire $annee_universitaire): self
    {
        $this->annee_universitaire = $annee_universitaire;

        return $this;
    }

    /**
     * @return Collection<int, Bibliotheque>
     */
    public function getBibliotheques(): Collection
    {
        return $this->bibliotheques;
    }

    public function addBibliotheque(Bibliotheque $bibliotheque): self
    {
        if (!$this->bibliotheques->contains($bibliotheque)) {
            $this->bibliotheques->add($bibliotheque);
            $bibliotheque->setEtudiant($this);
        }

        return $this;
    }

    public function removeBibliotheque(Bibliotheque $bibliotheque): self
    {
        if ($this->bibliotheques->removeElement($bibliotheque)) {
            // set the owning side to null (unless already changed)
            if ($bibliotheque->getEtudiant() === $this) {
                $bibliotheque->setEtudiant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Portfolio>
     */
    public function getPortfolios(): Collection
    {
        return $this->portfolios;
    }

    public function addPortfolio(Portfolio $portfolio): self
    {
        if (!$this->portfolios->contains($portfolio)) {
            $this->portfolios->add($portfolio);
            $portfolio->setEtudiant($this);
        }

        return $this;
    }

    public function removePortfolio(Portfolio $portfolio): self
    {
        if ($this->portfolios->removeElement($portfolio)) {
            // set the owning side to null (unless already changed)
            if ($portfolio->getEtudiant() === $this) {
                $portfolio->setEtudiant(null);
            }
        }

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): self
    {
        // unset the owning side of the relation if necessary
        if ($users === null && $this->users !== null) {
            $this->users->setEtudiant(null);
        }

        // set the owning side of the relation if necessary
        if ($users !== null && $users->getEtudiant() !== $this) {
            $users->setEtudiant($this);
        }

        $this->users = $users;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Semestre|null
     */
    public function getSemestre(): ?Semestre
    {
        return $this->semestre;
    }

    public function getSemestreId(): ?int
    {
        return $this->semestre?->getId();
    }

    public function setSemestreId($semestre)
    {
    }

    /**
     * @param Semestre|null $semestre
     */
    public function setSemestre(?Semestre $semestre): void
    {
        $this->semestre = $semestre;
    }

    public function removeSemestre(): void
    {
        $this->semestre = null;
    }

    public function getDepartement(): int
    {
        return $this->semestre?->getAnnee()?->getDiplome()?->getDepartement()?->getId();
    }

    public function setDepartement($departement)
    {
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setEtudiant($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getEtudiant() === $this) {
                $notification->setEtudiant(null);
            }
        }

        return $this;
    }

    public function isOptAlternance(): ?bool
    {
        return $this->opt_alternance;
    }

    public function setOptAlternance(bool $opt_alternance): static
    {
        $this->opt_alternance = $opt_alternance;

        return $this;
    }

    public function isOptStage(): ?bool
    {
        return $this->opt_stage;
    }

    public function setOptStage(bool $opt_stage): static
    {
        $this->opt_stage = $opt_stage;

        return $this;
    }

    public function getAnneeSortie(): ?int
    {
        return $this->annee_sortie;
    }

    public function setAnneeSortie(?int $annee_sortie): static
    {
        $this->annee_sortie = $annee_sortie;

        return $this;
    }
}
