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

    #[ORM\OneToMany(mappedBy: 'page', targetEntity: OrdreTrace::class)]
    private Collection $ordreTraces;

    #[ORM\OneToOne(mappedBy: 'page', targetEntity: OrdrePage::class)]
    private ?OrdrePage $ordrePage = null;

    public function __construct()
    {
        $this->trace = new ArrayCollection();
        $this->portfolio = new ArrayCollection();
        $this->pageTraces = new ArrayCollection();
        $this->ordreTraces = new ArrayCollection();
    }

    /**
     * @return OrdrePage|null
     */
    public function getOrdrePage(): ?OrdrePage
    {
        return $this->ordrePage;
    }

    /**
     * @param OrdrePage|null $ordrePage
     */
    public function setOrdrePage(?OrdrePage $ordrePage): void
    {
        $this->ordrePage = $ordrePage;
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

    /**
     * @return Collection<int, OrdreTrace>
     */
    public function getOrdreTraces(): Collection
    {
        return $this->ordreTraces;
    }

    public function addOrdreTrace(OrdreTrace $ordreTrace): static
    {
        if (!$this->ordreTraces->contains($ordreTrace)) {
            $this->ordreTraces->add($ordreTrace);
            $ordreTrace->setPage($this);
        }

        return $this;
    }

    public function removeOrdreTrace(OrdreTrace $ordreTrace): static
    {
        if ($this->ordreTraces->removeElement($ordreTrace)) {
            // set the owning side to null (unless already changed)
            if ($ordreTrace->getPage() === $this) {
                $ordreTrace->setPage(null);
            }
        }

        return $this;
    }
}
