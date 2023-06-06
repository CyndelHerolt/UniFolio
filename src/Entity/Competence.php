<?php

namespace App\Entity;

use App\Repository\CompetenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompetenceRepository::class)]
class Competence
{
    #[ORM\Id]
//    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $nom_court = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $couleur = null;

    #[ORM\OneToMany(mappedBy: 'competences', targetEntity: Validation::class)]
    private Collection $validations;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $ue = null;

    #[ORM\ManyToOne(inversedBy: 'competences')]
    private ?ApcReferentiel $referentiel = null;

    #[ORM\OneToMany(mappedBy: 'competences', targetEntity: ApcNiveau::class, cascade: ['persist'])]
    private Collection $apcNiveaux;

    public function __construct()
    {
        $this->validations = new ArrayCollection();
        $this->apcNiveaux = new ArrayCollection();
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNomCourt(): ?string
    {
        return $this->nom_court;
    }

    public function setNomCourt(?string $nom_court): self
    {
        $this->nom_court = $nom_court;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    /**
     * @return Collection<int, Validation>
     */
    public function getValidations(): Collection
    {
        return $this->validations;
    }

    public function addValidation(Validation $validation): self
    {
        if (!$this->validations->contains($validation)) {
            $this->validations->add($validation);
            $validation->setCompetences($this);
        }

        return $this;
    }

    public function removeValidation(Validation $validation): self
    {
        if ($this->validations->removeElement($validation)) {
            // set the owning side to null (unless already changed)
            if ($validation->getCompetences() === $this) {
                $validation->setCompetences(null);
            }
        }

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getUe(): ?string
    {
        return $this->ue;
    }

    public function setUe(?string $ue): self
    {
        $this->ue = $ue;

        return $this;
    }

    public function getReferentiel(): ?ApcReferentiel
    {
        return $this->referentiel;
    }

    public function setReferentiel(?ApcReferentiel $referentiel): self
    {
        $this->referentiel = $referentiel;

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
            $apcNiveau->setCompetences($this);
        }

        return $this;
    }

    public function removeApcNiveau(ApcNiveau $apcNiveau): self
    {
        if ($this->apcNiveaux->removeElement($apcNiveau)) {
            // set the owning side to null (unless already changed)
            if ($apcNiveau->getCompetences() === $this) {
                $apcNiveau->setCompetences(null);
            }
        }

        return $this;
    }
}
