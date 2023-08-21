<?php

namespace App\Twig\Components;

use App\Controller\BaseController;
use App\Entity\Commentaire;
use App\Entity\Trace;
use App\Form\CommentaireType;
use App\Repository\TraceRepository;
use Faker\Provider\Base;
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
class TraceEvalCardComponent extends BaseController
{
    use DefaultActionTrait;

    #[LiveProp]
    public int $id;

//    #[LiveProp(writable: true)]
//    public ?string $contenu = '';

    public function __construct(
        public TraceRepository       $traceRepository,
        private FormFactoryInterface $formFactory,
        private RequestStack         $requestStack, // <-- Injection du RequestStack
    )
    {

        // Créez une instance de votre entité Commentaire
        $commentaire = new Commentaire();

        // Créez votre formulaire CommentaireType
        $this->form = $this->formFactory->create(CommentaireType::class, $commentaire);
        $this->commentForm = $this->form->createView();
    }

//    #[LiveAction]
//    public function handleComment(): Response
//    {
//        dd('hello');
//        $request = $this->requestStack->getCurrentRequest();
//        $this->form->handleRequest($request);
//
//        if ($this->form->isSubmitted() && $this->form->isValid()) {
//            // Traiter le formulaire comme avant ici
//            dd($this->form->getData());
//
//            // Puis rediriger ou faire autre chose selon votre flux de travail
//            return new Response('Votre commentaire a été publié.');
//        }
//
//        // Créer et renvoyer une réponse avec le formulaire non valide pour que l'utilisateur puisse le corriger
//        // Vous pouvez également inclure du contenu supplémentaire dans votre réponse ici.
//        return new Response('Erreur de validation du formulaire.');
//    }

    public function getTrace(): ?Trace
    {
        return $this->traceRepository->find($this->id);
    }
}