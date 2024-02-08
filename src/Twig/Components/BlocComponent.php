<?php

namespace App\Twig\Components;

use App\Components\Editeur\EditeurRegistry;
use App\Entity\Bloc;
use App\Entity\Element;
use App\Entity\PagePerso;
use App\Entity\PortfolioPerso;
use App\Repository\BlocRepository;
use App\Repository\ElementRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('BlocComponent')]
class BlocComponent
{
    use DefaultActionTrait;

    #[LiveProp(writable: true, updateFromParent: true)]
    public ?Bloc $bloc;

    #[LiveProp(writable: true)]
    public ?PagePerso $page;

    #[LiveProp(writable: true, updateFromParent: true)]
    public ?PortfolioPerso $portfolioPerso;

    public function __construct(
        protected EditeurRegistry          $editeurRegistry,
        protected ElementRepository        $elementRepository,
        protected BlocRepository           $blocRepository,
    )
    {
        $this->elements = $this->editeurRegistry->getElements();
    }

    #[LiveAction]
    public function newElement(#[LiveArg] ?int $blocId, #[LiveArg] ?string $type): void
    {
        $bloc = $this->blocRepository->find($blocId);
        $element = new Element();
        $element->setOrdre(count($bloc->getElements()) + 1);
        $element->setBloc($bloc);
        $element->setType($type);
        $this->elementRepository->save($element);
        $elements = $this->elementRepository->findByBloc($bloc);
        foreach ($elements as $e) {
            $bloc->addElement($e);
        }
        $this->bloc = $bloc;
    }

    #[LiveAction]
    public function deleteElement(#[LiveArg] ?int $elementId): void
    {
        $element = $this->elementRepository->find($elementId);
        $bloc = $element->getBloc();
        $this->elementRepository->delete($element);
        $elements = $this->elementRepository->findByBloc($bloc);
        $ordre = 1;
        foreach ($elements as $e) {
            $e->setOrdre($ordre);
            $this->elementRepository->save($e);
            $ordre++;
        }
        $this->bloc = $bloc;
    }

//    #[LiveAction]
//    public function saveElement(#[LiveArg] ?int $elementId, #[LiveArg] ?string $content): void
//    {
//        $element = $this->elementRepository->find($elementId);
//        $element->setContenu($content);
//        $this->elementRepository->save($element);
//        $this->bloc = $element->getBloc();
//    }

    #[LiveAction]
    public function upElement(#[LiveArg] ?int $elementId): void
    {
        $element = $this->elementRepository->find($elementId);
        $ordre = $element->getOrdre();
        $bloc = $element->getBloc();
        if ($ordre > 1) {
            $elements = $this->elementRepository->findByBloc($bloc);
            foreach ($elements as $e) {
                if ($e->getOrdre() == $ordre - 1) {
                    $e->setOrdre($e->getOrdre() + 1);
                    $this->elementRepository->save($e);
                }
            }
            $element->setOrdre($ordre - 1);
            $this->elementRepository->save($element);
        }
        $this->bloc = $bloc;
    }

    #[LiveAction]
    public function downElement(#[LiveArg] ?int $elementId): void
    {
        $element = $this->elementRepository->find($elementId);
        $ordre = $element->getOrdre();
        $bloc = $element->getBloc();
        if ($ordre != count($bloc->getElements())) {
            $elements = $this->elementRepository->findByBloc($bloc);
            foreach ($elements as $e) {
                if ($e->getOrdre() == $ordre + 1) {
                    $e->setOrdre($e->getOrdre() - 1);
                    $this->elementRepository->save($e);
                }
            }
            $element->setOrdre($ordre + 1);
            $this->elementRepository->save($element);
        }
        $this->bloc = $bloc;
    }

    #[LiveAction]
    public function setCol(#[LiveArg] ?int $elementId, #[LiveArg] ?int $col): void
    {
        $element = $this->elementRepository->find($elementId);
        $element->setColonne('col-' . $col);
        $this->elementRepository->save($element);
        $this->bloc = $element->getBloc();
    }

    #[LiveAction]
    public function align(#[LiveArg] ?int $elementId, #[LiveArg] ?string $align): void
    {
        $element = $this->elementRepository->find($elementId);
        $element->setAlign($align);
        $this->elementRepository->save($element);
        $this->bloc = $element->getBloc();
    }

}