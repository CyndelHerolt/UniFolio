<?php

namespace App\Entity;

use App\Repository\SemestreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SemestreRepository::class)]
class Semestre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_modification = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    #[ORM\Column(nullable: true)]
    private ?int $ordre_annee = null;

    #[ORM\Column(nullable: true)]
    private ?bool $actif = null;

    #[ORM\Column(nullable: true)]
    private ?int $nb_groupes_cm = null;

    #[ORM\Column(nullable: true)]
    private ?int $nb_groupes_td = null;

    #[ORM\Column(nullable: true)]
    private ?int $nb_groupes_tp = null;

    #[ORM\ManyToOne(inversedBy: 'semestres')]
    private ?Annee $annee = null;

    #[ORM\ManyToMany(targetEntity: TypeGroupe::class, mappedBy: 'semestre')]
    private Collection $typeGroupes;

    public function __construct()
    {
        $this->typeGroupes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(?\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->date_modification;
    }

    public function setDateModification(?\DateTimeInterface $date_modification): self
    {
        $this->date_modification = $date_modification;

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

    public function getOrdreAnnee(): ?int
    {
        return $this->ordre_annee;
    }

    public function setOrdreAnnee(?int $ordre_annee): self
    {
        $this->ordre_annee = $ordre_annee;

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

    public function getNbGroupesCm(): ?int
    {
        return $this->nb_groupes_cm;
    }

    public function setNbGroupesCm(?int $nb_groupes_cm): self
    {
        $this->nb_groupes_cm = $nb_groupes_cm;

        return $this;
    }

    public function getNbGroupesTd(): ?int
    {
        return $this->nb_groupes_td;
    }

    public function setNbGroupesTd(?int $nb_groupes_td): self
    {
        $this->nb_groupes_td = $nb_groupes_td;

        return $this;
    }

    public function getNbGroupesTp(): ?int
    {
        return $this->nb_groupes_tp;
    }

    public function setNbGroupesTp(?int $nb_groupes_tp): self
    {
        $this->nb_groupes_tp = $nb_groupes_tp;

        return $this;
    }

    public function getAnnee(): ?Annee
    {
        return $this->annee;
    }

    public function setAnnee(?Annee $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * @return Collection<int, TypeGroupe>
     */
    public function getTypeGroupes(): Collection
    {
        return $this->typeGroupes;
    }

    public function addTypeGroupe(TypeGroupe $typeGroupe): self
    {
        if (!$this->typeGroupes->contains($typeGroupe)) {
            $this->typeGroupes->add($typeGroupe);
            $typeGroupe->addSemestre($this);
        }

        return $this;
    }

    public function removeTypeGroupe(TypeGroupe $typeGroupe): self
    {
        if ($this->typeGroupes->removeElement($typeGroupe)) {
            $typeGroupe->removeSemestre($this);
        }

        return $this;
    }
}
