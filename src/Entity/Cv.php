<?php

namespace App\Entity;

use App\Repository\CvRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CvRepository::class)]
class Cv
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_modification = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $intitule = null;

    #[ORM\ManyToOne(inversedBy: 'cvs')]
    private ?Etudiant $etudiant = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $langues = null;

//    #[ORM\Column(length: 255, nullable: true)]
//    private ?string $soft_skills = null;
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $soft_skills = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $hard_skills = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reseaux = null;

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

    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiant $etudiant): self
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSoftSkills(): array
    {
        return $this->soft_skills;
    }

    public function setSoftSkills(?array $soft_skills): self
    {
        $this->soft_skills = $soft_skills;

        return $this;
    }

    public function getHardSkills(): array
    {
        return $this->hard_skills;
    }

    public function setHardSkills(?array $hard_skills): self
    {
        $this->hard_skills = $hard_skills;

        return $this;
    }

    public function getLangues(): ?string
    {
        return $this->langues;
    }

    public function setLangues(?string $langues): self
    {
        $this->langues = $langues;

        return $this;
    }

    public function getReseaux(): ?string
    {
        return $this->reseaux;
    }

    public function setReseaux(?string $reseaux): self
    {
        $this->reseaux = $reseaux;

        return $this;
    }
}
