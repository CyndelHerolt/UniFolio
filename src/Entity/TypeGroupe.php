<?php

namespace App\Entity;

use App\Repository\TypeGroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeGroupeRepository::class)]
class TypeGroupe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $libelle = null;

    #[ORM\Column(nullable: true)]
    private ?bool $mutualise = null;

    #[ORM\Column(nullable: true)]
    private ?int $ordre_semestre = null;

    #[ORM\ManyToMany(targetEntity: Semestre::class, inversedBy: 'typeGroupes')]
    private Collection $semestre;

    #[ORM\OneToMany(mappedBy: 'type_groupe', targetEntity: Groupe::class)]
    private Collection $groupes;

    public function __construct()
    {
        $this->semestre = new ArrayCollection();
        $this->groupes = new ArrayCollection();
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

    public function isMutualise(): ?bool
    {
        return $this->mutualise;
    }

    public function setMutualise(?bool $mutualise): self
    {
        $this->mutualise = $mutualise;

        return $this;
    }

    public function getOrdreSemestre(): ?int
    {
        return $this->ordre_semestre;
    }

    public function setOrdreSemestre(?int $ordre_semestre): self
    {
        $this->ordre_semestre = $ordre_semestre;

        return $this;
    }

    /**
     * @return Collection<int, Semestre>
     */
    public function getSemestre(): Collection
    {
        return $this->semestre;
    }

    public function addSemestre(Semestre $semestre): self
    {
        if (!$this->semestre->contains($semestre)) {
            $this->semestre->add($semestre);
        }

        return $this;
    }

    public function removeSemestre(Semestre $semestre): self
    {
        $this->semestre->removeElement($semestre);

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
            $groupe->setTypeGroupe($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getTypeGroupe() === $this) {
                $groupe->setTypeGroupe(null);
            }
        }

        return $this;
    }
}
