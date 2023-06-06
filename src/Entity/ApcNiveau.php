<?php

namespace App\Entity;

use App\Repository\ApcNiveauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApcNiveauRepository::class)]
class ApcNiveau
{
    #[ORM\Id]
//    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    #[ORM\Column(nullable: true)]
    private ?int $ordre = null;

    #[ORM\ManyToOne(inversedBy: 'apcNiveaux')]
    private ?Competence $competences = null;

    #[ORM\OneToMany(mappedBy: 'niveaux', targetEntity: ApcApprentissageCritique::class)]
    private Collection $apcApprentissageCritiques;

    #[ORM\ManyToOne(inversedBy: 'apcNiveaux')]
    private ?Annee $annees = null;

    public function __construct()
    {
        $this->apcApprentissageCritiques = new ArrayCollection();
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

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getCompetences(): ?Competence
    {
        return $this->competences;
    }

    public function setCompetences(?Competence $competences): self
    {
        $this->competences = $competences;

        return $this;
    }

    /**
     * @return Collection<int, ApcApprentissageCritique>
     */
    public function getApcApprentissageCritiques(): Collection
    {
        return $this->apcApprentissageCritiques;
    }

    public function addApcApprentissageCritique(ApcApprentissageCritique $apcApprentissageCritique): self
    {
        if (!$this->apcApprentissageCritiques->contains($apcApprentissageCritique)) {
            $this->apcApprentissageCritiques->add($apcApprentissageCritique);
            $apcApprentissageCritique->setNiveaux($this);
        }

        return $this;
    }

    public function removeApcApprentissageCritique(ApcApprentissageCritique $apcApprentissageCritique): self
    {
        if ($this->apcApprentissageCritiques->removeElement($apcApprentissageCritique)) {
            // set the owning side to null (unless already changed)
            if ($apcApprentissageCritique->getNiveaux() === $this) {
                $apcApprentissageCritique->setNiveaux(null);
            }
        }

        return $this;
    }

    public function getAnnees(): ?Annee
    {
        return $this->annees;
    }

    public function setAnnees(?Annee $annees): self
    {
        $this->annees = $annees;

        return $this;
    }
}
