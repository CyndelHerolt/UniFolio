<?php
/*
 * Copyright (c) 2024. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */

namespace App\Controller;

use App\Components\Editeur\EditeurRegistry;
use App\Entity\Element;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditeurController extends AbstractController
{
    public function __construct(
        private readonly EditeurRegistry $editeurRegistry
    )
    {
    }

    #[Route('/editeur', name: 'app_editeur')]
    public function index(): Response
    {
        dump($this->editeurRegistry->getElements());

        return $this->render('editeur/index.html.twig', [
            'elements' => $this->editeurRegistry->getElements(),
        ]);
    }

    #[Route('/editeur/element/new/{element}', name: 'app_element_new')]
    public function new(
        ?string $element,
    ): Response
    {
//        dd($element);
        $typeElement = $this->editeurRegistry->getTypeElement($element);
//        dd($typeElement);
        $typeElement->create($element);

        return $this->render('editeur/index.html.twig', [
            'elements' => $this->editeurRegistry->getElements(),
        ]);
    }
//
//    #[Route('/editeur/element/update/{element}', name: 'app_element_update')]
//    public function update(?Element $element): Response
//    {
//
//
//        $typeElement = $this->editeurRegistry->getTypeElement($element->getType());
//        dd($typeElement);
//        $typeElement->sauvegarde($element);
//
////        $element = new Element();
////        $element->setType($type);
////        $element->setOrdre(1);
////        $element->setContenu('test');
////        $element->setCouleurPrim('blue');
////        $element->setCouleurSec('red');
////        $element->setWidth(100);
////        $element->setHeight(100);
//
//        return $this->render('editeur/index.html.twig', [
//            'elements' => $this->editeurRegistry->getElements(),
//        ]);
//    }
}
