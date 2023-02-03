<?php

namespace App\Entity;

use App\Repository\EtudiantRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
class Etudiant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mail_perso = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mail_univ = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getMailPerso(): ?string
    {
        return $this->mail_perso;
    }

    public function setMailPerso(?string $mail_perso): self
    {
        $this->mail_perso = $mail_perso;

        return $this;
    }

    public function getMailUniv(): ?string
    {
        return $this->mail_univ;
    }

    public function setMailUniv(?string $mail_univ): self
    {
        $this->mail_univ = $mail_univ;

        return $this;
    }
}
