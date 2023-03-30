<?php

namespace App\Components\SuiviEtudiant;

use App\Entity\Etudiant;
use App\Repository\EtudiantRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('suivi_etudiant_table')]
class SuiviEtudiantTableComponent
{
    public int $id;

    public function __construct(private EtudiantRepository $etudiantRepository)
    {
    }

    public function getEtudiant(): Etudiant
    {
        return $this->etudiantRepository->find($this->id);
    }
}