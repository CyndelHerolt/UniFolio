<?php
/*
 * Copyright (c) 2024. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Entity;

use App\Repository\BlocRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlocRepository::class)]
class Bloc
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $ordre = null;

    #[ORM\ManyToMany(targetEntity: PagePerso::class, inversedBy: 'blocs')]
    private Collection $pages;

    #[ORM\OneToMany(mappedBy: 'bloc', targetEntity: Element::class)]
    private Collection $elements;

    #[ORM\Column(length: 255)]
    private ?string $justify = "justify-content-start";

    #[ORM\Column(length: 255)]
    private ?string $direction = "flex-row";

    #[ORM\Column(length: 255)]
    private ?string $color = null;

    #[ORM\Column(length: 255)]
    private ?string $bg_color = null;

    #[ORM\Column(length: 255)]
    private ?string $font_size = null;

    public function __construct()
    {
        $this->pages = new ArrayCollection();
        $this->elements = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, PagePerso>
     */
    public function getPages(): Collection
    {
        return $this->pages;
    }

    public function addPage(PagePerso $page): static
    {
        if (!$this->pages->contains($page)) {
            $this->pages->add($page);
        }

        return $this;
    }

    public function removePage(PagePerso $page): static
    {
        $this->pages->removeElement($page);

        return $this;
    }

    /**
     * @return Collection<int, Element>
     */
    public function getElements(): Collection
    {
        return $this->elements;
    }

    public function addElement(Element $element): static
    {
        if (!$this->elements->contains($element)) {
            $this->elements->add($element);
            $element->setBloc($this);
        }

        return $this;
    }

    public function removeElement(Element $element): static
    {
        if ($this->elements->removeElement($element)) {
            // set the owning side to null (unless already changed)
            if ($element->getBloc() === $this) {
                $element->setBloc(null);
            }
        }

        return $this;
    }

    public function getJustify(): ?string
    {
        return $this->justify;
    }

    public function setJustify(string $justify): static
    {
        $this->justify = $justify;

        return $this;
    }

    public function getDirection(): ?string
    {
        return $this->direction;
    }

    public function setDirection(string $direction): static
    {
        $this->direction = $direction;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getBgColor(): ?string
    {
        return $this->bg_color;
    }

    public function setBgColor(string $bg_color): static
    {
        $this->bg_color = $bg_color;

        return $this;
    }

    public function getFontSize(): ?string
    {
        return $this->font_size;
    }

    public function setFontSize(string $font_size): static
    {
        $this->font_size = $font_size;

        return $this;
    }
}
