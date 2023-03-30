<?php

namespace App\Entity;

use App\Repository\PortfolioPageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PortfolioPageRepository::class)]
class PortfolioPage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $ordre = null;

    #[ORM\ManyToOne(inversedBy: 'portfolioPages')]
    private ?Portfolio $OrdrePage = null;

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

    public function getOrdrePage(): ?Portfolio
    {
        return $this->OrdrePage;
    }

    public function setOrdrePage(?Portfolio $OrdrePage): self
    {
        $this->OrdrePage = $OrdrePage;

        return $this;
    }
}
