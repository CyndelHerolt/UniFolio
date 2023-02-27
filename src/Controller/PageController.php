<?php

namespace App\Controller;

use App\Entity\Page;
use App\Form\PageType;
use App\Repository\PageRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/page', name: 'app_page')]
    public function index(
        PageRepository $pageRepository,
    ): Response
    {
        return $this->render('page/index.html.twig', [
            'pages' => $pageRepository->findAll(),
        ]);
    }

    #[Route('/page/new/{id}', name: 'app_page_new')]
    public function new(
        Request         $request,
        PageRepository  $pageRepository,
        Security        $security,
    ): Response
    {
        $user = $security->getUser()->getEtudiant();
        $page = new Page();

        $form = $this->createForm(PageType::class, $page, ['user' => $user]);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pageRepository->save($page, true);

            return $this->redirectToRoute('app_page');
        }

//        $trace = $traceRepository->findOneBy(['id' => $id]);

        return $this->render('page/new.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
//            'trace' => $trace,
        ]);
    }
}
