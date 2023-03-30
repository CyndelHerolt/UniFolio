<?php

namespace App\Components\SuiviEtudiant;

use App\Repository\GroupeRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('all_suivi_etudiant')]
class AllSuiviEtudiantComponent
{

    public function __construct(
        #[Required] public Security $security,
        private GroupeRepository $groupeRepository
    )
    {}

    public function getAllSuiviEtudiant(): array
    {
        $enseignant = $this->security->getUser()->getEnseignant();
        $groupes = $this->groupeRepository->findBy(['enseignant' => $enseignant]);

        $etudiants = [];
        foreach ($groupes as $groupe) {
            $etudiants = array_merge($etudiants, $groupe->getEtudiants()->toArray());
        }

        return $etudiants;
    }

}