<?php

namespace App\Components\Portfolio;

use App\Repository\PortfolioRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('all_portfolio')]
class AllPortfolioComponent
{
    public function __construct(
        public PortfolioRepository $portfolioRepository,
        #[Required] public Security $security
    )
    {

    }

    //cette solution suppose que chaque portfolio est associé à au moins une page. Si ce n'est pas le cas, il faudra adapter la méthode en conséquence
    public function getAllPortfolio(): array
    {
        // Récupérer les portfolios de l'utilisateur connecté
         $etudiant = $this->security->getUser()->getEtudiant();
        return $this->portfolioRepository->findBy(['etudiant' => $etudiant]);
    }

}
