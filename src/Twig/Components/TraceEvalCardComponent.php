<?php

namespace App\Twig\Components;

use App\Entity\Commentaire;
use App\Entity\Trace;
use App\Form\CommentaireType;
use App\Repository\TraceRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('TraceEvalCardComponent')]

class TraceEvalCardComponent
{
    use DefaultActionTrait;

    #[LiveProp]
    public int $id;

    #[LiveProp(writable: true)]
    public ?string $commentaire = '';

    public function __construct(
        public TraceRepository $traceRepository,
        private FormFactoryInterface $formFactory,
        private RequestStack $requestStack, // <-- Injection du RequestStack
    )
    {
        // Créez une instance de votre entité Commentaire
        $commentaire = new Commentaire();

        // Créez votre formulaire CommentaireType
//        $this->commentForm = $this->formFactory->create(CommentaireType::class, $commentaire);
    }

    #[LiveAction]
    public function handleComment(): Response
    {
        $commentaire = new Commentaire();

        $form = $this->formFactory->create(CommentaireType::class, $commentaire);
            dd($commentaire);

        $request = $this->requestStack->getCurrentRequest();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traiter le formulaire comme avant ici

            // Puis rediriger ou faire autre chose selon votre flux de travail
            return new Response('Votre commentaire a été publié.');
        }

        // Créer et renvoyer une réponse avec le formulaire non valide pour que l'utilisateur puisse le corriger
        // Vous pouvez également inclure du contenu supplémentaire dans votre réponse ici.
        return new Response('Erreur de validation du formulaire.');
    }

    public function getTrace(): ?Trace
    {
        return $this->traceRepository->find($this->id);
    }
}