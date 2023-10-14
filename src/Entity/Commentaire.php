<?php
/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_modification = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?Portfolio $portfolio = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?Trace $trace = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?Enseignant $enseignant = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    #[ORM\Column]
    private ?bool $visibilite = null;

    #[ORM\OneToOne(mappedBy: 'commentaire', cascade: ['persist', 'remove'])]
    private ?Notification $notification = null;

    #[ORM\Column(nullable: true)]
    private ?int $commentaire_parent = null;

    #[ORM\Column(nullable: true)]
    private ?int $commentaire_enfant = null;

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

    public function getPortfolio(): ?Portfolio
    {
        return $this->portfolio;
    }

    public function setPortfolio(?Portfolio $portfolio): self
    {
        $this->portfolio = $portfolio;

        return $this;
    }

    public function getTrace(): ?Trace
    {
        return $this->trace;
    }

    public function setTrace(?Trace $trace): self
    {
        $this->trace = $trace;

        return $this;
    }

    public function getEnseignant(): ?Enseignant
    {
        return $this->enseignant;
    }

    public function setEnseignant(?Enseignant $enseignant): self
    {
        $this->enseignant = $enseignant;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function isVisibilite(): ?bool
    {
        return $this->visibilite;
    }

    public function setVisibilite(bool $visibilite): static
    {
        $this->visibilite = $visibilite;

        return $this;
    }

    public function getNotification(): ?Notification
    {
        return $this->notification;
    }

    public function setNotification(?Notification $notification): static
    {
        // unset the owning side of the relation if necessary
        if ($notification === null && $this->notification !== null) {
            $this->notification->setCommentaire(null);
        }

        // set the owning side of the relation if necessary
        if ($notification !== null && $notification->getCommentaire() !== $this) {
            $notification->setCommentaire($this);
        }

        $this->notification = $notification;

        return $this;
    }

    public function getCommentaireParent(): ?int
    {
        return $this->commentaire_parent;
    }

    public function setCommentaireParent(?int $commentaire_parent): static
    {
        $this->commentaire_parent = $commentaire_parent;

        return $this;
    }

    public function getCommentaireEnfant(): ?int
    {
        return $this->commentaire_enfant;
    }

    public function setCommentaireEnfant(?int $commentaire_enfant): static
    {
        $this->commentaire_enfant = $commentaire_enfant;

        return $this;
    }
}
