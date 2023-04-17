<?php

namespace App\Components\SuiviEtudiant;

use App\Repository\EtudiantRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('all_suivi_etudiant')]
class AllSuiviEtudiantComponent
{

    public function __construct(
        private EtudiantRepository $etudiantRepository,
    )
    {}

    public function getAllSuiviEtudiant(): array
    {
        $etudiants = $this->etudiantRepository->findAll();

        return $etudiants;
    }

}
