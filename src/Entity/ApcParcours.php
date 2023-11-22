<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Entity;

use App\Repository\ApcParcoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApcParcoursRepository::class)]
class ApcParcours
{
    #[ORM\Id]
//    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $actif = true;

    #[ORM\ManyToOne(inversedBy: 'apcParcours')]
    private ?ApcReferentiel $ApcReferentiel = null;

    #[ORM\OneToMany(mappedBy: 'apcParcours', targetEntity: Diplome::class)]
    private Collection $diplomes;

    #[ORM\OneToMany(mappedBy: 'apcParcours', targetEntity: Groupe::class)]
    private Collection $groupes;

    #[ORM\ManyToMany(targetEntity: ApcNiveau::class, mappedBy: 'apcParcours')]
    private Collection $apcNiveaux;

    #[ORM\Column]
    private ?bool $formationContinue = null;

    public function __construct()
    {
        $this->diplomes = new ArrayCollection();
        $this->groupes = new ArrayCollection();
        $this->apcNiveaux = new ArrayCollection();
    }

    public function setId(?int $id): self
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getActif(): ?int
    {
        return $this->actif;
    }

    public function setActif(?int $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getApcReferentiel(): ?ApcReferentiel
    {
        return $this->ApcReferentiel;
    }

    public function setApcReferentiel(?ApcReferentiel $ApcReferentiel): self
    {
        $this->ApcReferentiel = $ApcReferentiel;

        return $this;
    }

    /**
     * @return Collection<int, Diplome>
     */
    public function getDiplomes(): Collection
    {
        return $this->diplomes;
    }

    public function addDiplome(Diplome $diplome): self
    {
        if (!$this->diplomes->contains($diplome)) {
            $this->diplomes->add($diplome);
            $diplome->setApcParcours($this);
        }

        return $this;
    }

    public function removeDiplome(Diplome $diplome): self
    {
        if ($this->diplomes->removeElement($diplome)) {
            // set the owning side to null (unless already changed)
            if ($diplome->getApcParcours() === $this) {
                $diplome->setApcParcours(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Groupe>
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes->add($groupe);
            $groupe->setApcParcours($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getApcParcours() === $this) {
                $groupe->setApcParcours(null);
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

    public function addApcNiveau(ApcNiveau $apcNiveau): static
    {
        if (!$this->apcNiveaux->contains($apcNiveau)) {
            $this->apcNiveaux->add($apcNiveau);
            $apcNiveau->addApcParcour($this);
        }

        return $this;
    }

    public function removeApcNiveau(ApcNiveau $apcNiveau): static
    {
        if ($this->apcNiveaux->removeElement($apcNiveau)) {
            $apcNiveau->removeApcParcour($this);
        }

        return $this;
    }

    public function isFormationContinue(): ?bool
    {
        return $this->formationContinue;
    }

    public function setFormationContinue(bool $formationContinue): static
    {
        $this->formationContinue = $formationContinue;

        return $this;
    }
}
