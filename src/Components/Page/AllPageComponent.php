<?php

namespace App\Components\Page;

use App\Repository\BibliothequeRepository;
use App\Repository\PageRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('all_page')]
class AllPageComponent
{
    public function __construct(
//        protected PageRepository $pageRepository,
        protected BibliothequeRepository $bibliothequeRepository,
        #[Required] public Security $security
    )
    {}

    //cette solution suppose que chaque page est associée à au moins une trace. Si ce n'est pas le cas, il faudra adapter la méthode en conséquence
    public function getAllPage(): array
    {
        // Récupérer la bibliothèque de l'utilisateur connecté
        $etudiant = $this->security->getUser()->getEtudiant();
        $biblio = $this->bibliothequeRepository->findOneBy(['etudiant' => $etudiant]);

        // Récupérer les traces de la bibliothèque
        $traces = $biblio->getTraces();

        // Récupérer les pages associées aux traces
        $pages = [];
        foreach ($traces as $trace) {
            $pages = array_merge($pages, $trace->getPages()->toArray());
//            Si deux pages sont les mêmes, ne les afficher qu'une seule fois
            $pages = array_unique($pages, SORT_REGULAR);
        }

        return $pages;
    }

}
