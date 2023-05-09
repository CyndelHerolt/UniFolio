<?php

namespace App\Entity;

use App\Repository\EnseignantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnseignantRepository::class)]
class Enseignant
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
    private ?string $mail_univ = null;

    #[ORM\OneToMany(mappedBy: 'enseignant', targetEntity: Groupe::class)]
    private Collection $groupes;

    #[ORM\OneToMany(mappedBy: 'enseignant', targetEntity: Validation::class)]
    private Collection $validations;

    #[ORM\OneToMany(mappedBy: 'enseignant', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    #[ORM\OneToOne(mappedBy: 'enseignant', cascade: ['persist', 'remove'])]
    private ?Users $users = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mail_perso = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telephone = null;

    /**
     * @var \Doctrine\Common\Collections\Collection<int, \App\Entity\EnseignantDepartement>
     */
    #[ORM\OneToMany(mappedBy: 'enseignant', targetEntity: EnseignantDepartement::class, cascade: ['persist', 'remove'])]
    private Collection $enseignantDepartements;

    #[ORM\Column(length: 75, nullable: true)]
    private ?string $username = null;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->validations = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->enseignantDepartements = new ArrayCollection();
    }

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

    public function getMailUniv(): ?string
    {
        return $this->mail_univ;
    }

    public function setMailUniv(?string $mail_univ): self
    {
        $this->mail_univ = $mail_univ;

        return $this;
    }

    /**
     * @return Collection<int, Groupe>
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes->add($groupe);
            $groupe->setEnseignant($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getEnseignant() === $this) {
                $groupe->setEnseignant(null);
            }
        }

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
            $validation->setEnseignant($this);
        }

        return $this;
    }

    public function removeValidation(Validation $validation): self
    {
        if ($this->validations->removeElement($validation)) {
            // set the owning side to null (unless already changed)
            if ($validation->getEnseignant() === $this) {
                $validation->setEnseignant(null);
            }
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
            $commentaire->setEnseignant($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getEnseignant() === $this) {
                $commentaire->setEnseignant(null);
            }
        }

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): self
    {
        // unset the owning side of the relation if necessary
        if ($users === null && $this->users !== null) {
            $this->users->setEnseignant(null);
        }

        // set the owning side of the relation if necessary
        if ($users !== null && $users->getEnseignant() !== $this) {
            $users->setEnseignant($this);
        }

        $this->users = $users;

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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection|EnseignantDepartement[]
     */
    public function getEnseignantDepartements(): Collection
    {
        return $this->enseignantDepartements;
    }

    public function addEnseignantDepartement(EnseignantDepartement $enseignantDepartement): self
    {
        if (!$this->enseignantDepartements->contains($enseignantDepartement)) {
            $this->enseignantDepartements[] = $enseignantDepartement;
            $enseignantDepartement->setEnseignant($this);
        }

        return $this;
    }

    public function removeEnseignantDepartement(EnseignantDepartement $enseignantDepartement): self
    {
        if ($this->enseignantDepartements->contains($enseignantDepartement)) {
            $this->enseignantDepartements->removeElement($enseignantDepartement);
            // set the owning side to null (unless already changed)
            if ($enseignantDepartement->getEnseignant() === $this) {
                $enseignantDepartement->setEnseignant(null);
            }
        }

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }
}
