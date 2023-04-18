<?php

namespace App\Entity;

use App\Repository\DiplomeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiplomeRepository::class)]
class Diplome
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $sigle = null;

    #[ORM\ManyToOne(inversedBy: 'departement')]
    private ?Departement $departement;

    #[ORM\OneToOne(mappedBy: 'diplomes', cascade: ['persist', 'remove'])]
    private ?Annee $annee = null;

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

    public function getSigle(): ?string
    {
        return $this->sigle;
    }

    public function setSigle(?string $sigle): self
    {
        $this->sigle = $sigle;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getDepartement(): Collection
    {
        return $this->departement;
    }

    /**
     * @param Collection $departement
     */
    public function setDepartement(Collection $departement): void
    {
        $this->departement = $departement;
    }

    public function getAnnee(): ?Annee
    {
        return $this->annee;
    }

    public function setAnnee(?Annee $annee): self
    {
        // unset the owning side of the relation if necessary
        if ($annee === null && $this->annee !== null) {
            $this->annee->setDiplomes(null);
        }

        // set the owning side of the relation if necessary
        if ($annee !== null && $annee->getDiplomes() !== $this) {
            $annee->setDiplomes($this);
        }

        $this->annee = $annee;

        return $this;
    }
}
