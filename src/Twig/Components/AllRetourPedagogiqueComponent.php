<?php

namespace App\Twig\Components;

use App\Controller\BaseController;
use App\Repository\BibliothequeRepository;
use App\Repository\CommentaireRepository;
use App\Repository\TraceRepository;
use App\Repository\ValidationRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('AllRetourPedagogiqueComponent')]
final class AllRetourPedagogiqueComponent extends BaseController
{
    public function __construct(
        public ValidationRepository  $validationRepository,
        public CommentaireRepository $commentaireRepository,
        public BibliothequeRepository $bibliothequeRepository,
        public TraceRepository       $traceRepository,
        #[Required] public Security  $security
    )
    {
    }

    public function getAllRetourPedagogique(): array
    {
        // Récupérer la bibliothèque de l'utilisateur connecté
        $etudiant = $this->security->getUser()->getEtudiant();
        $biblio = $this->bibliothequeRepository->findOneBy(['etudiant' => $etudiant]);

        $traces = $this->traceRepository->findBy(['bibliotheque' => $biblio]);

        $retourPedagogiques = [];
        foreach ($traces as $trace) {
            foreach ($trace->getValidations() as $validation) {
                if ($validation->isEtat() !== 0) {
                $retourPedagogiques[] = $validation;
                }
            }
            foreach ($trace->getCommentaires() as $commentaire) {
                if ($commentaire->isVisibilite()) {
                    $retourPedagogiques[] = $commentaire;
                }
            }
        }

        usort($retourPedagogiques, function($a, $b) {
            return $b->getDateCreation() <=> $a->getDateCreation();
        });

        $retourPedagogiques = array_slice($retourPedagogiques, 0, 4);

        return $retourPedagogiques;
    }
}
