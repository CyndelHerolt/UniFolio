<?php
/*
 * Copyright (c) 2024. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Entity;

use App\Repository\PortfolioPersoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PortfolioPersoRepository::class)]
class PortfolioPerso
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $intitule = null;

    #[ORM\Column]
    private ?bool $visibilite = null;

    #[ORM\ManyToOne(inversedBy: 'portfolioPersos')]
    private ?Etudiant $etudiant = null;

    #[ORM\OneToMany(mappedBy: 'portfolio', targetEntity: PagePerso::class)]
    private Collection $pagePersos;

    public function __construct()
    {
        $this->pagePersos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): static
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function isVisibilite(): ?bool
    {
        return $this->visibilite;
    }

    public function setVisibilite(bool $visibilite): static
    {
        $this->visibilite = $visibilite;

        return $this;
    }

    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiant $etudiant): static
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    /**
     * @return Collection<int, PagePerso>
     */
    public function getPagePersos(): Collection
    {
        return $this->pagePersos;
    }

    public function addPagePerso(PagePerso $pagePerso): static
    {
        if (!$this->pagePersos->contains($pagePerso)) {
            $this->pagePersos->add($pagePerso);
            $pagePerso->setPortfolio($this);
        }

        return $this;
    }

    public function removePagePerso(PagePerso $pagePerso): static
    {
        if ($this->pagePersos->removeElement($pagePerso)) {
            // set the owning side to null (unless already changed)
            if ($pagePerso->getPortfolio() === $this) {
                $pagePerso->setPortfolio(null);
            }
        }

        return $this;
    }
}
