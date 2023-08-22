<?php

namespace App\Twig\Components;

use App\Controller\BaseController;
use App\Entity\Commentaire;
use App\Entity\Portfolio;
use App\Form\CommentaireType;
use App\Repository\PortfolioRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('PortfolioEvalCardComponent')]
final class PortfolioEvalCardComponent extends BaseController
{
    use DefaultActionTrait;

    #[LiveProp]
    public int $id;

    public function __construct(
        public PortfolioRepository       $portfolioRepository,
        private FormFactoryInterface $formFactory,
        private RequestStack         $requestStack,
    )
    {

        // Créez une instance de votre entité Commentaire
        $commentaire = new Commentaire();

        // Créez votre formulaire CommentaireType
        $this->form = $this->formFactory->create(CommentaireType::class, $commentaire);
        $this->commentForm = $this->form->createView();
    }

    public function getPortfolio(): ?Portfolio
    {
        return $this->portfolioRepository->find($this->id);
    }
}
