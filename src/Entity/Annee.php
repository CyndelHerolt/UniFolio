<?php

namespace App\Entity;

use App\Repository\AnneeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnneeRepository::class)]
class Annee
{
    #[ORM\Id]
//    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    #[ORM\Column(nullable: true)]
    private ?int $ordre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle_long = null;

    #[ORM\Column(nullable: true)]
    private ?bool $opt_alternance = null;

    #[ORM\Column(nullable: true)]
    private ?bool $actif = null;

    #[ORM\ManyToOne(targetEntity: Diplome::class, inversedBy: 'annees')]
    #[ORM\JoinColumn(name: 'diplome_id', referencedColumnName: 'id', nullable: true)]
    private ?Diplome $diplome = null;

    #[ORM\OneToMany(mappedBy: 'annee', targetEntity: Semestre::class)]
    private Collection $semestres;

    #[ORM\OneToMany(mappedBy: 'annee', targetEntity: Bibliotheque::class)]
    private Collection $bibliotheques;

    #[ORM\OneToMany(mappedBy: 'années', targetEntity: ApcNiveau::class)]
    private Collection $apcNiveaux;

    #[ORM\OneToMany(mappedBy: 'annee', targetEntity: Portfolio::class)]
    private Collection $portfolio;

    public function __construct()
    {
        $this->semestres = new ArrayCollection();
        $this->bibliotheques = new ArrayCollection();
        $this->apcNiveaux = new ArrayCollection();
        $this->portfolio = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getLibelleLong(): ?string
    {
        return $this->libelle_long;
    }

    public function setLibelleLong(?string $libelle_long): self
    {
        $this->libelle_long = $libelle_long;

        return $this;
    }

    public function isOptAlternance(): ?bool
    {
        return $this->opt_alternance;
    }

    public function setOptAlternance(?bool $opt_alternance): self
    {
        $this->opt_alternance = $opt_alternance;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(?bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getDiplome(): ?Diplome
    {
        return $this->diplome;
    }

    public function setDiplome(?Diplome $diplome): self
    {
        $this->diplome = $diplome;

        return $this;
    }


    /**
     * @return Collection<int, Semestre>
     */
    public function getSemestres(): Collection
    {
        return $this->semestres;
    }

    public function addSemestre(Semestre $semestre): self
    {
        if (!$this->semestres->contains($semestre)) {
            $this->semestres->add($semestre);
            $semestre->setAnnee($this);
        }

        return $this;
    }

    public function removeSemestre(Semestre $semestre): self
    {
        if ($this->semestres->removeElement($semestre)) {
            // set the owning side to null (unless already changed)
            if ($semestre->getAnnee() === $this) {
                $semestre->setAnnee(null);
            }
        }

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
            $bibliotheque->setAnnee($this);
        }

        return $this;
    }

    public function removeBibliotheque(Bibliotheque $bibliotheque): self
    {
        if ($this->bibliotheques->removeElement($bibliotheque)) {
            // set the owning side to null (unless already changed)
            if ($bibliotheque->getAnnee() === $this) {
                $bibliotheque->setAnnee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ApcNiveau>
     */
    public function getApcNiveaux(): Collection
    {
        return $this->apcNiveaux;
    }

    public function addApcNiveau(ApcNiveau $apcNiveau): self
    {
        if (!$this->apcNiveaux->contains($apcNiveau)) {
            $this->apcNiveaux->add($apcNiveau);
            $apcNiveau->setAnnees($this);
        }

        return $this;
    }

    public function removeApcNiveau(ApcNiveau $apcNiveau): self
    {
        if ($this->apcNiveaux->removeElement($apcNiveau)) {
            // set the owning side to null (unless already changed)
            if ($apcNiveau->getAnnees() === $this) {
                $apcNiveau->setAnnees(null);
            }
        }

        return $this;
    }

    public function getPortfolio(): Collection
    {
        return $this->portfolio;
    }

    public function setPortfolio(Collection $portfolio): void
    {
        $this->portfolio = $portfolio;
    }


}
