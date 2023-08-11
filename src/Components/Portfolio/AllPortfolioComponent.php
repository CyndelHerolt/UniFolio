<?php

namespace App\Components\Portfolio;

use App\Controller\BaseController;
use App\Entity\Portfolio;
use App\Repository\PortfolioRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('all_portfolio')]
class AllPortfolioComponent extends BaseController
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $selectedOrdreDate = '';

    #[LiveProp(writable: true)]
    public string $selectedOrdreValidation = '';

    #[LiveProp(writable: true)]
    /** @var Portfolio[] */
    public array $allPortfolios = [];

    public function __construct(
        public PortfolioRepository  $portfolioRepository,
        #[Required] public Security $security
    )
    {
    }

    #[LiveAction]
    public function changePortfolioOrdreDate()
    {
//        dd($this->selectedOrdreDate);
        $this->selectedOrdreValidation = '';
        $this->allPortfolios = $this->getAllPortfolio();
    }

    #[LiveAction]
    public function changePortfolioOrdreValidation()
    {
//        dd($this->selectedOrdreValidation);
        $this->selectedOrdreDate = '';
        $this->allPortfolios = $this->getAllPortfolio();
    }

    public function getAllPortfolio(): array
    {

        $ordreDate = $this->selectedOrdreDate != null ? $this->selectedOrdreDate : null;

        $ordreValidation = $this->selectedOrdreValidation != null ? $this->selectedOrdreValidation : null;

        // Récupérer les portfolios de l'utilisateur connecté
        $etudiant = $this->security->getUser()->getEtudiant();
        $portfolios = $this->portfolioRepository->findBy(['etudiant' => $etudiant]);

        if (!empty($portfolios)) {
            if (!empty($ordreDate)) {
                // Sort by dateModification column
                $portfolios = $this->portfolioRepository->findBy(['etudiant' => $etudiant], ['date_modification' => $ordreDate]);
            }
        }

        return $portfolios;
    }
}
