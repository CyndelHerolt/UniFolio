<?php

namespace App\Entity;

use App\Repository\BibliothequeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BibliothequeRepository::class)]
class Bibliotheque
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bibliotheques', cascade: ['persist', 'remove'])]
    private ?Etudiant $etudiant = null;

    #[ORM\ManyToOne(inversedBy: 'bibliotheques')]
    private ?AnneeUniversitaire $annee_universitaire = null;

    #[ORM\ManyToOne(inversedBy: 'bibliotheques')]
    private ?Annee $annee = null;

    #[ORM\OneToMany(mappedBy: 'bibliotheque', targetEntity: Trace::class)]
    private Collection $traces;

    public function __construct()
    {
        $this->traces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiant $etudiant): self
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    public function getAnneeUniversitaire(): ?AnneeUniversitaire
    {
        return $this->annee_universitaire;
    }

    public function setAnneeUniversitaire(?AnneeUniversitaire $annee_universitaire): self
    {
        $this->annee_universitaire = $annee_universitaire;

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
     * @return Collection<int, Trace>
     */
    public function getTraces(): Collection
    {
        return $this->traces;
    }

    public function addTrace(Trace $trace): self
    {
        if (!$this->traces->contains($trace)) {
            $this->traces->add($trace);
            $trace->setBibliotheque($this);
        }

        return $this;
    }

    public function removeTrace(Trace $trace): self
    {
        if ($this->traces->removeElement($trace)) {
            // set the owning side to null (unless already changed)
            if ($trace->getBibliotheque() === $this) {
                $trace->setBibliotheque(null);
            }
        }

        return $this;
    }
}
