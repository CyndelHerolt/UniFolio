<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Entity;

use App\Repository\DepartementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepartementRepository::class)]
class Departement
{
    #[ORM\Id]
//    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $logo_name = null;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $tel_contact = null;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $couleur = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'departement', targetEntity: Diplome::class)]
    #[ORM\OrderBy(value: ['libelle' => 'ASC'])]
    private Collection $diplomes;

    /**
     * @var Collection<int, EnseignantDepartement>
     */
    #[ORM\OneToMany(mappedBy: 'departement', targetEntity: EnseignantDepartement::class, cascade: ['persist', 'remove'])]
    private Collection $enseignantDepartements;

    #[ORM\OneToMany(mappedBy: 'departement', targetEntity: ApcReferentiel::class)]
    private Collection $apcReferentiels;

    public function __construct()
    {
        $this->diplomes = new ArrayCollection();
        $this->enseignants = new ArrayCollection();
        $this->apcReferentiels = new ArrayCollection();
        $this->enseignantDepartements = new ArrayCollection();
    }

    public function removeTrace(Trace $trace): self
    {
        $this->trace->removeElement($trace);

        return $this;
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

    public function getLogoName(): ?string
    {
        return $this->logo_name;
    }

    public function setLogoName(?string $logo_name): self
    {
        $this->logo_name = $logo_name;

        return $this;
    }

    public function getTelContact(): ?string
    {
        return $this->tel_contact;
    }

    public function setTelContact(?string $tel_contact): self
    {
        $this->tel_contact = $tel_contact;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
            $diplome->setDepartement($this);
        }

        return $this;
    }

    public function removeDiplome(Diplome $diplome): self
    {
        if ($this->diplomes->removeElement($diplome)) {
            $diplome->removeDepartement($this);
        }

        return $this;
    }

    /**
     * @return Collection|EnseignantDepartement[]
     */
    public function getEnseignantDepartements(): Collection
    {
        return $this->enseignantDepartements;
    }

    public function addEnseignantDepartement(EnseignantDepartement $enseignantDepartement): self
    {
        if (!$this->enseignantDepartements->contains($enseignantDepartement)) {
            $this->enseignantDepartements[] = $enseignantDepartement;
            $enseignantDepartement->setDepartement($this);
        }

        return $this;
    }

    public function removeEnseignantDepartement(EnseignantDepartement $enseignantDepartement): self
    {
        if ($this->enseignantDepartements->contains($enseignantDepartement)) {
            $this->enseignantDepartements->removeElement($enseignantDepartement);
            // set the owning side to null (unless already changed)
            if ($enseignantDepartement->getDepartement() === $this) {
                $enseignantDepartement->setDepartement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ApcReferentiel>
     */
    public function getApcReferentiels(): Collection
    {
        return $this->apcReferentiels;
    }

    public function addApcReferentiel(ApcReferentiel $apcReferentiel): self
    {
        if (!$this->apcReferentiels->contains($apcReferentiel)) {
            $this->apcReferentiels->add($apcReferentiel);
            $apcReferentiel->setDepartement($this);
        }

        return $this;
    }

    public function removeApcReferentiel(ApcReferentiel $apcReferentiel): self
    {
        if ($this->apcReferentiels->removeElement($apcReferentiel)) {
            // set the owning side to null (unless already changed)
            if ($apcReferentiel->getDepartement() === $this) {
                $apcReferentiel->setDepartement(null);
            }
        }

        return $this;
    }
}
