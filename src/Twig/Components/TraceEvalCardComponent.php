<?php

namespace App\Twig\Components;

use App\Controller\BaseController;
use App\Entity\Commentaire;
use App\Entity\Trace;
use App\Event\EvaluationEvent;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use App\Repository\TraceRepository;
use App\Repository\ValidationRepository;
use Faker\Provider\Base;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Service\Attribute\Required;
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

    #[LiveProp(writable: true)]
    public ?string $selectedValidation = '';

    private Commentaire $commentaire;

    #[LiveProp(writable: true)]
    public ?string $commentContent = '';

    #[LiveProp(writable: true)]
    public ?string $commentVisibility = 'true';

//    #[LiveProp(writable: true)]
//    public ?int $commentId = null;


//    #[LiveProp(writable: true)]
//    /** @var Trace[] */
//    public array $Trace = [];

    public function __construct(
        protected TraceRepository       $traceRepository,
        private FormFactoryInterface $formFactory,
        private RequestStack         $requestStack,
        protected ValidationRepository  $validationRepository,
        protected CommentaireRepository $commentaireRepository,
        #[Required] public Security  $security,
    )
    {
//        $this->Trace = $this->getTrace();

        // Créez une instance de votre entité Commentaire
        $this->commentaire = new Commentaire();

    }

    #[LiveAction]
    public function changeValidation(EventDispatcherInterface $eventDispatcher)
    {
        $selectedValidation = $this->selectedValidation;
        list($validationId, $state) = explode('-', $selectedValidation);
        $validationId = intval($validationId);
        $state = intval($state);

        $validation = $this->validationRepository->find($validationId);

        // Mettre à jour le validation dans la base de données
        $validation->setEtat($state);
        $validation->setEnseignant($this->security->getUser()->getEnseignant());
        $validation->setDateCreation(new \DateTime());
        if ($validation->getDateCreation() != null) {
            $validation->setDateModification(new \DateTime());
        }
        $this->validationRepository->save($validation, true);

        $event = new EvaluationEvent($validation);
        $eventDispatcher->dispatch($event, EvaluationEvent::EVALUATED);
    }

    #[LiveAction]
    public function handleComment() {
        if (!$this->commentContent) {
            return;
        }

        if ($this->commentVisibility == 'true') {
            $this->commentVisibility = true;
        } else {
            $this->commentVisibility = false;
        }

        $this->commentaire->setContenu($this->commentContent);
        $this->commentaire->setVisibilite((bool) $this->commentVisibility);
        $this->commentaire->setEnseignant($this->security->getUser()->getEnseignant());
        $this->commentaire->setTrace($this->getTrace());
        $this->commentaire->setDatecreation(new \DateTime());

        $this->commentaireRepository->save($this->commentaire, true);

        $this->commentContent = '';
        $this->commentVisibility = false;
        // Ajoutez cette ligne pour préparer une nouvelle entité Commentaire pour la prochaine utilisation
        $this->commentaire = new Commentaire();
    }

//    #[LiveAction]
//    public function deleteComment()
//    {
//        $com = $this->commentId;
////        dd($com);
//        $commentaire = $this->commentaireRepository->find($this->commentId);
//
//        if ($commentaire) {
//            $this->commentaireRepository->remove($commentaire, true);
//        }
//    }

    public function getTrace(): ?Trace
    {
        return $this->traceRepository->find($this->id);
    }
}