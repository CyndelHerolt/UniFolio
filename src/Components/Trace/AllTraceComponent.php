<?php

namespace App\Components\Trace;

use App\Controller\BaseController;
use App\Repository\BibliothequeRepository;
use App\Repository\CompetenceRepository;
use App\Repository\TraceRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
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
        RequestStack $requestStack,
    )
    {
        $this->requestStack = $requestStack;
    }

    public function getAllTrace(): array
    {
        $request = $this->requestStack->getCurrentRequest();

        // Récupérer la bibliothèque de l'utilisateur connecté
        $etudiant = $this->security->getUser()->getEtudiant();
        $biblio = $this->bibliothequeRepository->findOneBy(['etudiant' => $etudiant]);

        // Vérifier si la requête n'est pas null (ce sera le cas si le composant est appelé hors d'une requête),
        // puis récupérer le paramètre 'competence' de la requête
        $competence = [];
        $competence[] = $request ? $request->query->get('competence') : null;


        // Si un formulaire en méthode post est reçu
        if ($competence !== null) {
            if ($this->traceRepository->findByCompetence($competence) != null) {
                // On récupère les compétences sélectionnées
                $traces = $this->traceRepository->findByCompetence($competence);
            } else {
                // Sinon on récupère toutes les traces et "msg d'erreur"
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
