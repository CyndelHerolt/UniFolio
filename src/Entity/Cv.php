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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $soft_skills = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $hard_skills = null;

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

    public function getSoftSkills(): string
    {
        return $this->soft_skills;
    }

    public function setSoftSkills(?string $soft_skills): self
    {
        $this->soft_skills = $soft_skills;

        return $this;
    }

    public function getHardSkills(): string
    {
        return $this->hard_skills;
    }

    public function setHardSkills(?string $hard_skills): self
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

    public function getFormationDateDebut(): ?\DateTimeInterface
    {
        return $this->formation_date_debut;
    }

    public function setFormationDateDebut(?\DateTimeInterface $formation_date_debut): self
    {
        $this->formation_date_debut = $formation_date_debut;

        return $this;
    }

    public function getFormationDateFin(): ?\DateTimeInterface
    {
        return $this->formation_date_fin;
    }

    public function setFormationDateFin(?\DateTimeInterface $formation_date_fin): self
    {
        $this->formation_date_fin = $formation_date_fin;

        return $this;
    }

    public function getFormationLieu(): ?string
    {
        return $this->formation_lieu;
    }

    public function setFormationLieu(?string $formation_lieu): self
    {
        $this->formation_lieu = $formation_lieu;

        return $this;
    }

    public function getFormationDescription(): ?string
    {
        return $this->formation_description;
    }

    public function setFormationDescription(?string $formation_description): self
    {
        $this->formation_description = $formation_description;

        return $this;
    }

    public function getExperienceDateDebut(): ?\DateTimeInterface
    {
        return $this->experience_date_debut;
    }

    public function setExperienceDateDebut(\DateTimeInterface $experience_date_debut): self
    {
        $this->experience_date_debut = $experience_date_debut;

        return $this;
    }

    public function getExperiencePoste(): ?string
    {
        return $this->experience_poste;
    }

    public function setExperiencePoste(?string $experience_poste): self
    {
        $this->experience_poste = $experience_poste;

        return $this;
    }

    public function getFormationDiplome(): ?string
    {
        return $this->formation_diplome;
    }

    public function setFormationDiplome(?string $formation_diplome): self
    {
        $this->formation_diplome = $formation_diplome;

        return $this;
    }

    public function getExperienceLieu(): ?string
    {
        return $this->experience_lieu;
    }

    public function setExperienceLieu(?string $experience_lieu): self
    {
        $this->experience_lieu = $experience_lieu;

        return $this;
    }

    public function getExperienceDescription(): ?string
    {
        return $this->experience_description;
    }

    public function setExperienceDescription(?string $experience_description): self
    {
        $this->experience_description = $experience_description;

        return $this;
    }

    public function getLiensReseaux(): array
    {
        return $this->liens_reseaux;
    }

    public function setLiensReseaux(?array $liens_reseaux): self
    {
        $this->liens_reseaux = $liens_reseaux;

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
