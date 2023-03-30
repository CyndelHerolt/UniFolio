<?php

namespace App\Entity;

use App\Repository\TracePageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TracePageRepository::class)]
class TracePage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $ordre = null;

    #[ORM\ManyToOne(inversedBy: 'tracePages')]
    private ?Page $OrdrePage = null;

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

    public function getOrdrePage(): ?Page
    {
        return $this->OrdrePage;
    }

    public function setOrdrePage(?Page $OrdrePage): self
    {
        $this->OrdrePage = $OrdrePage;

        return $this;
    }
}
