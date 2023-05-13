<?php

namespace App\Entity;

use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupeRepository::class)]
class Groupe
{
    #[ORM\Id]
//    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $code_apogee = null;

    #[ORM\Column(nullable: true)]
    private ?int $ordre = null;

    #[ORM\ManyToOne(inversedBy: 'groupes')]
    private ?TypeGroupe $type_groupe = null;

    #[ORM\ManyToMany(targetEntity: Etudiant::class, mappedBy: 'groupe', cascade: ['persist', 'remove'])]
    private Collection $etudiants;

    public function __construct()
    {
        $this->etudiants = new ArrayCollection();
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

    public function getCodeApogee(): ?string
    {
        return $this->code_apogee;
    }

    public function setCodeApogee(?string $code_apogee): self
    {
        $this->code_apogee = $code_apogee;

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

    public function getTypeGroupe(): ?TypeGroupe
    {
        return $this->type_groupe;
    }

    public function setTypeGroupe(?TypeGroupe $type_groupe): self
    {
        $this->type_groupe = $type_groupe;

        return $this;
    }

    /**
     * @return Collection<int, Etudiant>
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }

    public function addEtudiant(Etudiant $etudiant): self
    {
        if (!$this->etudiants->contains($etudiant)) {
            $this->etudiants->add($etudiant);
            $etudiant->addGroupe($this);
        }

        return $this;
    }

    public function removeEtudiant(Etudiant $etudiant): self
    {
        if ($this->etudiants->removeElement($etudiant)) {
            $etudiant->removeGroupe($this);
        }

        return $this;
    }
}
