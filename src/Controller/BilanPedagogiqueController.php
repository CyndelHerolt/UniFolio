<?php
/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Controller;

use App\Entity\Portfolio;
use App\Repository\AnneeRepository;
use App\Repository\BibliothequeRepository;
use App\Repository\OrdrePageRepository;
use App\Repository\OrdreTraceRepository;
use App\Repository\PortfolioRepository;
use App\Repository\TraceRepository;
use App\Repository\ValidationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

#[Route('/etudiant')]
class BilanPedagogiqueController extends BaseController
{

    public function __construct(
        #[Required] public Security   $security,
        public AnneeRepository        $anneeRepository,
        public BibliothequeRepository $bibliothequeRepository,
        public TraceRepository        $traceRepository,
        public ValidationRepository   $validationRepository,
        public PortfolioRepository    $portfolioRepository,
        public OrdrePageRepository    $ordrePageRepository,
        public OrdreTraceRepository   $ordreTraceRepository,

    )
    {

    }

    #[Route('/bilan-pedagogique', name: 'app_bilan_pedagogique')]
    public function index(): Response
    {
        $data_user = $this->dataUserSession;

        if ($this->isGranted('ROLE_ETUDIANT')) {

            //Récupérer les portfolios de l'utilisateur connecté
            $etudiant = $this->security->getUser()->getEtudiant();
            $portfolios = $this->portfolioRepository->findBy(['etudiant' => $etudiant]);

            $evaluatedTraces = 0;

            $evaluatedCompetences = [];

            $validatedTraces = 0;

            $validatedCompetences = [];

            foreach ($portfolios as $portfolio) {
                $totalValidations = 0;
                $totalEtatNonZero = 0;
                $totalEtatTrois = 0;

                foreach ($portfolio->getOrdrePages() as $op) {
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
                            if (!isset($evaluatedCompetences[$label])) {
                                $evaluatedCompetences[$label] = ['total' => 0, 'etatNonZero' => 0];
                            }
                            if (!isset($validatedCompetences[$label])) {
                                $validatedCompetences[$label] = ['total' => 0, 'etatTrois' => 0];
                            }
                            $evaluatedCompetences[$label]['total']++;
                            $validatedCompetences[$label]['total']++;

                            if ($validation->isEtat() != 0) {
                                $evaluatedCompetences[$label]['etatNonZero']++;
                            }
                            if ($validation->isEtat() == 3) {
                                $validatedCompetences[$label]['etatTrois']++;
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

                foreach ($evaluatedCompetences as $label => $data) {
                    if ($data['total'] > 0) {
                        $evaluatedCompetences[$label]['percentage'] = round(($data['etatNonZero'] / $data['total']) * 100);
                    } else {
                        $evaluatedCompetences[$label]['percentage'] = 0;
                    }
                }

                foreach ($validatedCompetences as $label => $data) {
                    if ($data['total'] > 0) {
                        $validatedCompetences[$label]['percentage'] = round(($data['etatTrois'] / $data['total']) * 100);
                    } else {
                        $validatedCompetences[$label]['percentage'] = 0;
                    }
                }

                $evaluatedTraces = round($pourcentage);
                $validatedTraces = round($pourcentageValidated);
            }

            return $this->render('bilan_pedagogique/index.html.twig', [
                'evaluatedTraces' => $evaluatedTraces,
                'evaluatedCompetences' => $evaluatedCompetences,
                'validatedTraces' => $validatedTraces,
                'validatedCompetences' => $validatedCompetences,
                'portfolios' => $portfolios,
                'data_user' => $data_user,
            ]);
        } else {
            return $this->render('security/accessDenied.html.twig');
        }
    }
}
