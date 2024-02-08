<?php
/*
 * Copyright (c) 2024. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Entity;

use App\Repository\ElementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ElementRepository::class)]
class Element
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $ordre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = '';

    #[ORM\Column(length: 255)]
    private ?string $couleur_prim = '';

    #[ORM\Column(length: 255)]
    private ?string $couleur_sec = '';

    #[ORM\Column]
    private ?int $width = 0;

    #[ORM\Column]
    private ?int $height = 0;

    #[ORM\ManyToOne(inversedBy: 'elements')]
    private ?PagePerso $page = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'elements')]
    private ?Bloc $bloc = null;

    #[ORM\Column(length: 50)]
    private ?string $colonne = 'col-12';

    #[ORM\Column]
    private ?bool $edit = true;

    #[ORM\Column(length: 255)]
    private ?string $fontSize = '16px';

    #[ORM\Column(length: 255)]
    private ?string $fontWeight = '200';

    #[ORM\Column(length: 2)]
    private ?string $titleLvl = 'h1';

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $align = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): static
    {
        $this->ordre = $ordre;

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

    public function getCouleurPrim(): ?string
    {
        return $this->couleur_prim;
    }

    public function setCouleurPrim(string $couleur_prim): static
    {
        $this->couleur_prim = $couleur_prim;

        return $this;
    }

    public function getCouleurSec(): ?string
    {
        return $this->couleur_sec;
    }

    public function setCouleurSec(string $couleur_prim_sec): static
    {
        $this->couleur_sec = $couleur_prim_sec;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getPage(): ?PagePerso
    {
        return $this->page;
    }

    public function setPage(?PagePerso $page): static
    {
        $this->page = $page;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getBloc(): ?Bloc
    {
        return $this->bloc;
    }

    public function setBloc(?Bloc $bloc): static
    {
        $this->bloc = $bloc;

        return $this;
    }

    public function getColonne(): ?string
    {
        return $this->colonne;
    }

    public function setColonne(string $colonne): static
    {
        $this->colonne = $colonne;

        return $this;
    }

    public function isEdit(): ?bool
    {
        return $this->edit;
    }

    public function setEdit(bool $edit): static
    {
        $this->edit = $edit;

        return $this;
    }

    public function getFontSize(): ?string
    {
        return $this->fontSize;
    }

    public function setFontSize(string $fontSize): static
    {
        $this->fontSize = $fontSize;

        return $this;
    }

    public function getFontWeight(): ?string
    {
        return $this->fontWeight;
    }

    public function setFontWeight(string $fontWeight): static
    {
        $this->fontWeight = $fontWeight;

        return $this;
    }

    public function getTitleLvl(): ?string
    {
        return $this->titleLvl;
    }

    public function setTitleLvl(string $titleLvl): static
    {
        $this->titleLvl = $titleLvl;

        return $this;
    }

    public function getAlign(): ?string
    {
        return $this->align;
    }

    public function setAlign(?string $align): static
    {
        $this->align = $align;

        return $this;
    }
}
