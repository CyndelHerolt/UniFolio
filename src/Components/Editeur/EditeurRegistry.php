<?php

namespace App\Components\Editeur;

use App\Components\Editeur\Elements\AbstractElement;

class EditeurRegistry
{
    const TAG_TYPE_ELEMENT = 'editeur.element';

    private array $elements = [];

    public function registerElement($name, AbstractElement $element): void
    {
        $this->elements[$name] = $element;
    }

    public function getElement($name): AbstractElement
    {
        return $this->elements[$name];
    }

    public function getElements(): array
    {
        return $this->elements;
    }

    public function getTypeElement(?string $name): mixed
    {
        return $this->elements[$name];
    }
}