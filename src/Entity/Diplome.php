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

    #[ORM\ManyToMany(targetEntity: Departement::class, inversedBy: 'diplomes')]
    private Collection $departement;

    #[ORM\OneToOne(mappedBy: 'diplomes', cascade: ['persist', 'remove'])]
    private ?Annee $annee = null;

    public function __construct()
    {
        $this->departement = new ArrayCollection();
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
     * @return Collection<int, Departement>
     */
    public function getDepartementId(): Collection
    {
        return $this->departement;
    }

    public function addDepartementId(Departement $departementId): self
    {
        if (!$this->departement->contains($departementId)) {
            $this->departement->add($departementId);
        }

        return $this;
    }

    public function removeDepartementId(Departement $departementId): self
    {
        $this->departement->removeElement($departementId);

        return $this;
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
