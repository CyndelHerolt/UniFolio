<?php
/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Entity;

use App\Repository\DiplomeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiplomeRepository::class)]
class Diplome
{
    #[ORM\Id]
//    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $sigle = null;

    #[ORM\ManyToOne(inversedBy: 'departement')]
    private ?Departement $departement;

    #[ORM\OneToMany(mappedBy: 'diplome', targetEntity: Annee::class)]
    #[ORM\OrderBy(value: ['ordre' => 'ASC', 'libelle' => 'ASC'])]
    private Collection $annees;

    #[ORM\ManyToOne(inversedBy: 'diplomes')]
    private ?ApcParcours $apcParcours = null;

    /**
     * @return Collection
     */
    public function getAnnees(): Collection
    {
        return $this->annees;
    }

    /**
     * @param Collection $annees
     */
    public function setAnnees(Collection $annees): void
    {
        $this->annees = $annees;
    }

    /**
     * @param Collection $annees
     */
//    public function __construct(Collection $annees)
//    {
//        $this->annees = new ArrayCollection();
//    }



    public function addAnnee(Annee $annee): self
    {
        if (!$this->annees->contains($annee)) {
            $this->annees[] = $annee;
            $annee->setDiplome($this);
        }

        return $this;
    }

    public function removeAnnee(Annee $annee): self
    {
        if ($this->annees->contains($annee)) {
            $this->annees->removeElement($annee);
            // set the owning side to null (unless already changed)
            if ($annee->getDiplome() === $this) {
                $annee->setDiplome(null);
            }
        }

        return $this;
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

    public function getSigle(): ?string
    {
        return $this->sigle;
    }

    public function setSigle(?string $sigle): self
    {
        $this->sigle = $sigle;

        return $this;
    }

    public function getDepartement(): Departement
    {
        return $this->departement;
    }

    public function setDepartement(Departement $departement): void
    {
        $this->departement = $departement;
    }

    public function getApcParcours(): ?ApcParcours
    {
        return $this->apcParcours;
    }

    public function setApcParcours(?ApcParcours $apcParcours): self
    {
        $this->apcParcours = $apcParcours;

        return $this;
    }

}
