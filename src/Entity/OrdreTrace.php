<?php

namespace App\Entity;

use App\Repository\OrdreTraceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdreTraceRepository::class)]
class OrdreTrace
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ordreTraces', cascade: ['persist', 'remove'])]
    private ?Trace $trace = null;

    #[ORM\ManyToOne(inversedBy: 'ordreTracesPage')]
    private ?Page $page = null;

    #[ORM\Column(nullable: true)]
    private ?int $ordre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrace(): ?Trace
    {
        return $this->trace;
    }

    public function setTrace(?Trace $trace): static
    {
        $this->trace = $trace;

        return $this;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): static
    {
        $this->page = $page;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?int $ordre): static
    {
        $this->ordre = $ordre;

        return $this;
    }
}
