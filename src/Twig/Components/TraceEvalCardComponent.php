<?php

namespace App\Twig\Components;

use App\Controller\BaseController;
use App\Entity\Commentaire;
use App\Entity\Trace;
use App\Form\CommentaireType;
use App\Repository\TraceRepository;
use App\Repository\ValidationRepository;
use Faker\Provider\Base;
use Symfony\Bundle\SecurityBundle\Security;
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

//    #[LiveProp(writable: true)]
//    /** @var Trace[] */
//    public array $Trace = [];

    public function __construct(
        public TraceRepository       $traceRepository,
        private FormFactoryInterface $formFactory,
        private RequestStack         $requestStack,
        public ValidationRepository  $validationRepository,
        #[Required] public Security  $security,
    )
    {
//        $this->Trace = $this->getTrace();

        // Créez une instance de votre entité Commentaire
        $commentaire = new Commentaire();

        // Créez votre formulaire CommentaireType
        $this->form = $this->formFactory->create(CommentaireType::class, $commentaire);
        $this->commentForm = $this->form->createView();
    }

    #[LiveAction]
    public function changeValidation()
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
    }

    public function getTrace(): ?Trace
    {
        return $this->traceRepository->find($this->id);
    }
}