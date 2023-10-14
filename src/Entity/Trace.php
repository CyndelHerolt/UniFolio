<?php
/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Entity;

use App\Repository\TraceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TraceRepository::class)]
class Trace
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_modification = null;

    #[ORM\ManyToOne(inversedBy: 'traces')]
    private ?Bibliotheque $bibliotheque = null;

    #[ORM\OneToMany(mappedBy: 'trace', targetEntity: Validation::class, cascade: ['persist', 'remove'])]
    private Collection $validations;

    #[ORM\ManyToMany(targetEntity: Page::class, mappedBy: 'trace')]
    private Collection $pages;

    #[ORM\OneToMany(mappedBy: 'trace', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type_trace = null;

    #[ORM\Column(type: Types::JSON, length: 255, nullable: true)]
    private ?array $contenu = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $ordre = null;

    #[ORM\OneToOne(mappedBy: 'trace', targetEntity: OrdreTrace::class, cascade: ['persist', 'remove'])]
    private ?OrdreTrace $ordreTrace = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $legende = null;

    /**
     * @return OrdreTrace|null
     */
    public function getOrdreTrace(): ?OrdreTrace
    {
        return $this->ordreTrace;
    }

    /**
     * @param OrdreTrace|null $ordreTrace
     */
    public function setOrdreTrace(?OrdreTrace $ordreTrace): void
    {
        $this->ordreTrace = $ordreTrace;
    }

    public function __construct()
    {
        $this->date_creation = new \DateTimeImmutable();
        $this->date_modification = null;
        $this->validations = new ArrayCollection();
        $this->pages = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreation(): \DateTimeImmutable
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

    public function getBibliotheque(): ?Bibliotheque
    {
        return $this->bibliotheque;
    }

    public function setBibliotheque(?Bibliotheque $bibliotheque): self
    {
//        $this->bibliotheque = $bibliotheque;
//
//        return $this;

        $this->bibliotheque = $bibliotheque;

        $bibliotheque?->addTrace($this);

        return $this;
    }

    /**
     * @return Collection<int, Validation>
     */
    public function getValidations(): Collection
    {
        return $this->validations;
    }

    public function addValidation(Validation $validation): self
    {
        if (!$this->validations->contains($validation)) {
            $this->validations->add($validation);
            $validation->setTrace($this);
        }

        return $this;
    }

    public function removeValidation(Validation $validation): self
    {
        if ($this->validations->removeElement($validation)) {
            // set the owning side to null (unless already changed)
            if ($validation->getTrace() === $this) {
                $validation->setTrace(null);
            }
        }

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
            $page->addTrace($this);
        }

        return $this;
    }

    public function removePage(Page $page): self
    {
        if ($this->pages->removeElement($page)) {
            $page->removeTrace($this);
        }

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
            $commentaire->setTrace($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getTrace() === $this) {
                $commentaire->setTrace(null);
            }
        }

        return $this;
    }

    public function getTypeTrace(): ?string
    {
        return $this->type_trace;
    }

    public function setTypeTrace(?string $type_trace): self
    {
        $this->type_trace = $type_trace;

        return $this;
    }

    public function getContenu(): ?array
    {
        return $this->contenu;
    }

    public function setContenu(?array $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

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

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getLegende(): ?string
    {
        return $this->legende;
    }

    public function setLegende(string $legende): static
    {
        $this->legende = $legende;

        return $this;
    }
}
