<?php
/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Components\Cv;

use App\Entity\Cv;
use App\Repository\CvRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('cv_card')]
class CvCardComponent
{
    public int $id;

    public function __construct(private CvRepository $cvRepository)
    {}

    public function getCv(): Cv
    {
        return $this->cvRepository->find($this->id);
    }
}
