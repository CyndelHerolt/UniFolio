<?php

namespace App\Components\Trace;

use App\Controller\BaseController;
use App\Repository\BibliothequeRepository;
use App\Repository\CompetenceRepository;
use App\Repository\TraceRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('all_trace')]
class AllTraceComponent extends BaseController
{

    public function __construct(
        public TraceRepository        $traceRepository,
        public BibliothequeRepository $bibliothequeRepository,
        public CompetenceRepository   $competenceRepository,
        #[Required] public Security   $security,
    )
    {
    }

    public function getAllTrace(): array
    {
        // Récupérer la bibliothèque de l'utilisateur connecté
        $etudiant = $this->security->getUser()->getEtudiant();
        $biblio = $this->bibliothequeRepository->findOneBy(['etudiant' => $etudiant]);

        // todo: corriger l'affichage du message flash en cas de recherche infructueuse

        // Si un formulaire en méthode post est reçu
        if ($_POST) {
            if ($this->traceRepository->findByCompetence($_POST) != null) {
                // On récupère les compétences sélectionnées
                $traces = $this->traceRepository->findByCompetence($_POST);
            } else {
                $this->addFlash('danger', 'Aucune trace ne correspond à la recherche');
                $traces = $this->traceRepository->findBy(['bibliotheque' => $biblio]);
            }
        } else {
            $traces = $this->traceRepository->findBy(['bibliotheque' => $biblio]);
            // retirer les traces qui n'ont pas de type
            foreach ($traces as $key => $trace) {
                if ($trace->getTypeTrace() == null) {
                    unset($traces[$key]);
                }
            }
        }

        return $traces;
    }
}
