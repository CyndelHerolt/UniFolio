<?php

namespace App\Entity;

use App\Repository\PageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PageRepository::class)]
class Page
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $ordre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $intitule = null;

    #[ORM\ManyToMany(targetEntity: Trace::class, inversedBy: 'pages', fetch: "EAGER")]
    private Collection $trace;

    #[ORM\ManyToOne(inversedBy: 'pages')]
    private ?Templates $templates = null;

    #[ORM\ManyToMany(targetEntity: Portfolio::class, inversedBy: 'pages')]
    private Collection $portfolio;

    public function __construct()
    {
        $this->trace = new ArrayCollection();
        $this->portfolio = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection<int, Trace>
     */
    public function getTrace(): Collection
    {
        return $this->trace;
    }

    public function addTrace(Trace $trace): self
    {
        if (!$this->trace->contains($trace)) {
            $this->trace->add($trace);
        }

        return $this;
    }

    public function removeTrace(Trace $trace): self
    {
        $this->trace->removeElement($trace);

        return $this;
    }

    public function getTemplates(): ?Templates
    {
        return $this->templates;
    }

    public function setTemplates(?Templates $templates): self
    {
        $this->templates = $templates;

        return $this;
    }

    /**
     * @return Collection<int, Portfolio>
     */
    public function getPortfolio(): Collection
    {
        return $this->portfolio;
    }

    public function addPortfolio(Portfolio $portfolio): self
    {
        if (!$this->portfolio->contains($portfolio)) {
            $this->portfolio->add($portfolio);
        }

        return $this;
    }

    public function removePortfolio(Portfolio $portfolio): self
    {
        $this->portfolio->removeElement($portfolio);

        return $this;
    }
}
