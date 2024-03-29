<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Entity;

use App\Repository\PortfolioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PortfolioRepository::class)]
class Portfolio
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
    private ?string $intitule = null;

    #[ORM\ManyToMany(targetEntity: Page::class, mappedBy: 'portfolio')]
    private Collection $pages;

    #[ORM\ManyToOne(inversedBy: 'portfolios')]
    private ?Etudiant $etudiant = null;

    #[ORM\OneToMany(mappedBy: 'portfolio', targetEntity: Commentaire::class, cascade: ['persist', 'remove'])]
    private Collection $commentaires;

    #[ORM\Column]
    private ?bool $visibilite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $banniere = '/files_directory/banniere.jpg';

    #[ORM\OneToMany(mappedBy: 'portfolio', targetEntity: OrdrePage::class, cascade: ['persist', 'remove'])]
    private Collection $ordrePages;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'portfolio')]
    private ?Annee $annee = null;

    #[ORM\ManyToOne(inversedBy: 'portfolio')]
    private ?Cv $cv = null;

    #[ORM\Column]
    private ?bool $opt_search = null;

    public function __construct()
    {
        $this->date_creation = new \DateTimeImmutable();
        $this->date_modification = null;
        $this->pages = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->pagePortfolios = new ArrayCollection();
        $this->ordrePages = new ArrayCollection();
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

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(?string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * @return Collection<int, Page>
     */
    public function getPages(): Collection
    {
        return $this->pages;
    }

    public function addPage(Page $page): self
    {
        if (!$this->pages->contains($page)) {
            $this->pages->add($page);
            $page->addPortfolio($this);
        }

        return $this;
    }

    public function removePage(Page $page): self
    {
        if ($this->pages->removeElement($page)) {
            $page->removePortfolio($this);
        }

        return $this;
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

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setPortfolio($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getPortfolio() === $this) {
                $commentaire->setPortfolio(null);
            }
        }

        return $this;
    }

    public function isVisibilite(): ?bool
    {
        return $this->visibilite;
    }

    public function setVisibilite(bool $visibilite): self
    {
        $this->visibilite = $visibilite;

        return $this;
    }

    public function getVisibilite(): ?bool
    {
        return $this->visibilite;
    }

    public function getBanniere(): ?string
    {
        return $this->banniere;
    }

    public function setBanniere(?string $banniere): self
    {
        $this->banniere = $banniere;

        return $this;
    }

    /**
     * @return Collection<int, OrdrePage>
     */
    public function getOrdrePages(): Collection
    {
        return $this->ordrePages;
    }

    public function addOrdrePage(OrdrePage $ordrePage): static
    {
        if (!$this->ordrePages->contains($ordrePage)) {
            $this->ordrePages->add($ordrePage);
            $ordrePage->setPortfolio($this);
        }

        return $this;
    }

    public function removeOrdrePage(OrdrePage $ordrePage): static
    {
        if ($this->ordrePages->removeElement($ordrePage)) {
            // set the owning side to null (unless already changed)
            if ($ordrePage->getPortfolio() === $this) {
                $ordrePage->setPortfolio(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAnnee(): ?Annee
    {
        return $this->annee;
    }

    public function setAnnee(?Annee $annee): void
    {
        $this->annee = $annee;
    }

    public function getCv(): ?Cv
    {
        return $this->cv;
    }

    public function setCv(?Cv $cv): static
    {
        $this->cv = $cv;

        return $this;
    }

    public function isOptSearch(): ?bool
    {
        return $this->opt_search;
    }

    public function setOptSearch(bool $opt_search): static
    {
        $this->opt_search = $opt_search;

        return $this;
    }
}
