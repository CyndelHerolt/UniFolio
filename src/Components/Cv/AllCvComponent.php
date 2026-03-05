<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Components\Cv;

use App\Repository\CvRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('all_cv')]
class AllCvComponent
{
public function __construct(
    protected CvRepository $cvRepository,
    #[Required] public Security $security,
) {}


    public function getAllCv(): array
    {
        $etudiant = $this->security->getUser()->getEtudiant();
        return $this->cvRepository->findBy(['etudiant' => $etudiant]);
    }
}
