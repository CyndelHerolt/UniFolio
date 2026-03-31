<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Controller;

use App\Entity\Page;
use App\Entity\Trace;
use App\Form\PageType;
use App\Repository\BibliothequeRepository;
use App\Repository\CompetenceRepository;
use App\Repository\PageRepository;
use App\Repository\PortfolioRepository;
use App\Repository\TraceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Service\Attribute\Required;


class PageController extends BaseController
{
    public function __construct(
        protected PageRepository $pageRepository,
        protected PortfolioRepository $portfolioRepository,
    ) {
    }

    #[Route('/old/page/{portfolioId}/{pageId}/', name: 'app_old_page_show')]
    public function index(?int $pageId, ?int $portfolioId): Response
    {
        $portfolio = $this->portfolioRepository->findOneBy(['id' => $portfolioId]);
        $page = $this->pageRepository->findOneBy(['id' => $pageId]);
        $traces = $page->getTrace();

        return $this->render('page/old_show.html.twig', [
            'page' => $page,
            'portfolio' => $portfolio,
            'traces' => $traces,
        ]);
    }
}
