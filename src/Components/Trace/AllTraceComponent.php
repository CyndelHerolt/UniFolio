<?php
namespace App\Components\Trace;

use App\Repository\BibliothequeRepository;
use App\Repository\TraceRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('all_trace')]
class AllTraceComponent {

	public function __construct(
        public TraceRepository $traceRepository,
        public BibliothequeRepository $bibliothequeRepository,
        #[Required] public Security $security
    )
    {}

    public function getAllTrace(): array
    {
        // Récupérer la bibliothèque de l'utilisateur connecté
        $etudiant = $this->security->getUser()->getEtudiant();
        $biblio = $this->bibliothequeRepository->findOneBy(['etudiant' => $etudiant]);
        $traces = $this->traceRepository->findBy(['bibliotheque' => $biblio]);

        return $traces;
    }
}
