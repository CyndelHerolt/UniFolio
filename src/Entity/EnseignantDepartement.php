<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Entity;

use App\Repository\EnseignantDepartementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnseignantDepartementRepository::class)]
class EnseignantDepartement extends BaseEntity
{
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $defaut = false;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: Enseignant::class, inversedBy: 'enseignantDepartements')]
        private Enseignant $enseignant,
        #[ORM\ManyToOne(targetEntity: Departement::class, inversedBy: 'enseignantDepartements')]
        private Departement $departement
    ) {
//        $this->addRole('ROLE_PERMANENT');
//        $this->annee = (int) date('Y');
    }

    public function addRole(string $role): self
    {
        $roles = $this->getRoles();
        $roles[] = $role;
        $this->roles = json_encode($roles, JSON_THROW_ON_ERROR);

        return $this;
    }

    public function getRoles(): ?array
    {
        if ('' === $this->roles) {
            return [];
        }

        return json_decode($this->roles, false, 2, JSON_THROW_ON_ERROR);
    }

    public function setRoles(string $roles): self
    {
        $this->roles = $roles;

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

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * @throws \JsonException
     */
    public function clearRole(): self
    {
        $this->roles = json_encode([], JSON_THROW_ON_ERROR);

        return $this;
    }

    public function removeRole(string $role): self
    {
        $roles = $this->getRoles();
        $key = array_search($role, $roles, true);
        unset($roles[$key]);
        $roles = array_values($roles);
        $this->roles = json_encode($roles, JSON_THROW_ON_ERROR);

        return $this;
    }

    public function getDefaut(): ?bool
    {
        return $this->defaut;
    }

    public function setDefaut(bool $defaut): self
    {
        $this->defaut = $defaut;

        return $this;
    }
}
