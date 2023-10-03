<?php

namespace App\Entity;

use App\Repository\CvRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $langues = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $soft_skills = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $hard_skills = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $reseaux = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $poste = null;

    #[ORM\ManyToMany(targetEntity: Experience::class, inversedBy: 'cvs', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $experience;

    #[ORM\ManyToMany(targetEntity: Formation::class, inversedBy: 'cvs', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $formation;

    #[ORM\OneToMany(mappedBy: 'cv', targetEntity: Portfolio::class)]
    private Collection $portfolio;

    public function __construct()
    {
        $this->experience = new ArrayCollection();
        $this->formation = new ArrayCollection();
        $this->portfolio = new ArrayCollection();
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

    public function getLangues(): ?array
    {
        return $this->langues;
    }

    public function setLangues(?array $langues): self
    {
        $this->langues = $langues;

        return $this;
    }

    public function getReseaux(): ?array
    {
        return $this->reseaux;
    }

    public function setReseaux(?array $reseaux): self
    {
        $this->reseaux = $reseaux;

        return $this;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(?string $poste): self
    {
        $this->poste = $poste;

        return $this;
    }

    /**
     * @return Collection<int, Experience>
     */
    public function getExperience(): Collection
    {
        return $this->experience;
    }

    public function addExperience(Experience $experience): self
    {
        if (!$this->experience->contains($experience)) {
            $this->experience->add($experience);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): self
    {
        $this->experience->removeElement($experience);

        return $this;
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getFormation(): Collection
    {
        return $this->formation;
    }

    public function addFormation(Formation $formation): self
    {
        if (!$this->formation->contains($formation)) {
            $this->formation->add($formation);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        $this->formation->removeElement($formation);

        return $this;
    }

    /**
     * @return Collection<int, Portfolio>
     */
    public function getPortfolio(): Collection
    {
        return $this->portfolio;
    }

    public function addPortfolio(Portfolio $portfolio): static
    {
        if (!$this->portfolio->contains($portfolio)) {
            $this->portfolio->add($portfolio);
            $portfolio->setCv($this);
        }

        return $this;
    }

    public function removePortfolio(Portfolio $portfolio): static
    {
        if ($this->portfolio->removeElement($portfolio)) {
            // set the owning side to null (unless already changed)
            if ($portfolio->getCv() === $this) {
                $portfolio->setCv(null);
            }
        }

        return $this;
    }
}
