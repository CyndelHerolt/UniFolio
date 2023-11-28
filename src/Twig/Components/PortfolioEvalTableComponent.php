<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */

namespace App\Twig\Components;

use App\Controller\BaseController;
use App\Entity\Etudiant;
use App\Entity\Portfolio;
use App\Repository\EtudiantRepository;
use App\Repository\PortfolioRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('PortfolioEvalTableComponent')]
final class PortfolioEvalTableComponent extends BaseController
{
    use DefaultActionTrait;

    #[LiveProp]
    public int $id;

    public int $evaluatedTraces = 0;

    public array $evaluatedCompetences = [];

    public int $validatedTraces = 0;

    public array $validatedCompetences = [];

    public function __construct(
        public EtudiantRepository  $etudiantRepository,
        public PortfolioRepository $portfolioRepository
    )
    {
    }


    public function getEtudiant(): ?Etudiant
    {
        $etudiant = $this->etudiantRepository->find($this->id);

        $totalValidations = 0;
        $totalEtatNonZero = 0;
        $totalEtatTrois = 0;

        $this->portfolio = null;
        $portfolios = $this->portfolioRepository->findBy(['etudiant' => $etudiant, 'annee' => $etudiant->getSemestre()->getAnnee()]);

        if ($portfolios !== null) {
            foreach ($portfolios as $portfolio) {
                // si parmis les portfolios il y en a un qui a visibilite = 1, on le prend
                if ($portfolio->getVisibilite() == 1) {
                    $this->portfolio = $portfolio;
                    break;
                } else {
                    // sinon on prend le dernier portfolio
                    $this->portfolio = $portfolio;
                }
            }
        } else {
            dump($etudiant);
            $this->portfolio = null;
        }

        if ($this->portfolio !== null) {
            foreach ($this->portfolio->getOrdrePages() as $op) {
                foreach ($op->getPage()->getOrdreTraces() as $ot) {
                    $validations = $ot->getTrace()->getValidations();

                    $totalValidations += count($validations);

                    foreach ($validations as $validation) {
                        if ($validation->isEtat() != 0) {
                            $totalEtatNonZero++;
                        }
                        if ($validation->isEtat() == 3) {
                            $totalEtatTrois++;
                        }

                        $label = $validation->getApcNiveau()->getLibelle();
                        if (!isset($this->evaluatedCompetences[$label])) {
                            $this->evaluatedCompetences[$label] = ['total' => 0, 'etatNonZero' => 0];
                        }
                        if (!isset($this->validatedCompetences[$label])) {
                            $this->validatedCompetences[$label] = ['total' => 0, 'etatTrois' => 0];
                        }
                        $this->evaluatedCompetences[$label]['total']++;
                        $this->validatedCompetences[$label]['total']++;

                        if ($validation->isEtat() != 0) {
                            $this->evaluatedCompetences[$label]['etatNonZero']++;
                        }
                        if ($validation->isEtat() == 3) {
                            $this->validatedCompetences[$label]['etatTrois']++;
                        }
                    }
                }
            }

            if ($totalValidations > 0) {
                $pourcentage = ($totalEtatNonZero / $totalValidations) * 100;
            } else {
                $pourcentage = 0;
            }

            if ($totalValidations > 0) {
                $pourcentageValidated = ($totalEtatTrois / $totalValidations) * 100;
            } else {
                $pourcentageValidated = 0;
            }

            foreach ($this->evaluatedCompetences as $label => $data) {
                if ($data['total'] > 0) {
                    $this->evaluatedCompetences[$label]['percentage'] = round(($data['etatNonZero'] / $data['total']) * 100);
                } else {
                    $this->evaluatedCompetences[$label]['percentage'] = 0;
                }
            }

            foreach ($this->validatedCompetences as $label => $data) {
                if ($data['total'] > 0) {
                    $this->validatedCompetences[$label]['percentage'] = round(($data['etatTrois'] / $data['total']) * 100);
                } else {
                    $this->validatedCompetences[$label]['percentage'] = 0;
                }
            }

            $this->evaluatedTraces = round($pourcentage);
            $this->validatedTraces = round($pourcentageValidated);
        }

        return $etudiant;
    }
}
