<?php

namespace App\Entity;

use App\Repository\ApcApprentissageCritiqueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApcApprentissageCritiqueRepository::class)]
class ApcApprentissageCritique
{
    #[ORM\Id]
//    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $libelle = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $code = null;

    #[ORM\ManyToOne(inversedBy: 'apcApprentissageCritiques')]
    private ?ApcNiveau $niveaux = null;

    public function setId(?int $id): self
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getNiveaux(): ?ApcNiveau
    {
        return $this->niveaux;
    }

    public function setNiveaux(?ApcNiveau $niveaux): self
    {
        $this->niveaux = $niveaux;

        return $this;
    }
}
