<?php

namespace App\Controller;

use App\Entity\Page;
use App\Entity\Trace;
use App\Form\PageType;
use App\Repository\BibliothequeRepository;
use App\Repository\PageRepository;
use App\Repository\TraceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

class PageController extends AbstractController
{

    public function __construct(
        protected TraceRepository     $traceRepository,
        public BibliothequeRepository $bibliothequeRepository,
        #[Required] public Security   $security
    )
    {
    }

    #[Route('/page', name: 'app_page')]
    public function index(
        PageRepository $pageRepository,
    ): Response
    {

        //Récupérer la bibliothèque de l'utilisateur connecté
        $etudiant = $this->security->getUser()->getEtudiant();
        $biblio = $this->bibliothequeRepository->findOneBy(['etudiant' => $etudiant]);

        // Récupérer les traces de la bibliothèque
        $traces = $biblio->getTraces();

        if ($traces->isEmpty()) {
            $add = false;
        } else {
            $add = true;
        }

        // Récupérer les pages associées aux traces(donc les pages de l'étudiant connecté)
        $pages = [];
        foreach ($traces as $trace) {
            $pages = array_merge($pages, $trace->getPages()->toArray());
//            Si deux pages sont les mêmes, ne les afficher qu'une seule fois
            $pages = array_unique($pages, SORT_REGULAR);
        }

        return $this->render('page/index.html.twig', [
            'pages' => $pages,
            'add' => $add,
        ]);
    }

    #[Route('/page/new/{id}', name: 'app_page_new')]
    public function new(
        Request        $request,
        PageRepository $pageRepository,
        Security       $security,
    ): Response
    {
        $user = $security->getUser()->getEtudiant();
        $page = new Page();

        $form = $this->createForm(PageType::class, $page, ['user' => $user]);

        $trace = $form->get('trace')->getData();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($trace->isEmpty()) {
                $this->addFlash('danger', 'Veuillez sélectionner au moins une trace.');
            } else {
                $pageRepository->save($page, true);

                $this->addFlash('success', 'La trace a été ajoutée à la page avec succès.');
                return $this->redirectToRoute('app_page');
            }
        }


        return $this->render('page/new.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
        ]);
    }

    #[Route('/page/edit/{id}', name: 'app_page_edit')]
    public function edit(
        Request        $request,
        PageRepository $pageRepository,
        Security       $security,
        int            $id,
    ): Response
    {
        $user = $security->getUser()->getEtudiant();
        $page = $pageRepository->findOneBy(['id' => $id]);

        $form = $this->createForm(PageType::class, $page, ['user' => $user]);

        $trace = $form->get('trace')->getData();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //Si il n'y a pas de trace sélectionnée dans le formulaire
            if ($trace->isEmpty()) {
                $this->addFlash('danger', 'Veuillez sélectionner au moins une trace.');
            } else {
                $pageRepository->save($page, true);

                $this->addFlash('success', 'La trace a été ajoutée à la page avec succès.');
                return $this->redirectToRoute('app_page');
            }
        }

        return $this->render('page/edit.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
        ]);
    }

    #[Route('/page/delete/{id}', name: 'app_page_delete')]
    public function delete(
        Request        $request,
        PageRepository $pageRepository,
        string         $id,
    ): Response
    {
        $page = $pageRepository->find($id);

        $pageRepository->remove($page, true);
        $this->addFlash('success', 'La page a été supprimée avec succès.');
        return $this->redirectToRoute('app_page');
    }

    #[Route('/page/trace/{id}', name: 'app_add_to_page')]
    public function addTrace(
        Request        $request,
        PageRepository $pageRepository,
        Security       $security,
        int            $id,
    ): Response
    {
        $user = $security->getUser()->getEtudiant();
        $page = $pageRepository->findOneBy(['id' => $id]);
//        dump($page->getTrace());
//        die();

        //Récupérer les traces liées à la page dont l'id est passé en paramètre
        $existingTraces = $page->getTrace();
//        dump($existingTraces);
//        die();

        $form = $this->createFormBuilder($page)
            ->add('trace', EntityType::class, [
                'class' => Trace::class,
                'query_builder' => function (TraceRepository $traceRepository) use ($user, $page) {
                    return $traceRepository->createQueryBuilder('t')
                        ->join('t.bibliotheque', 'b')
                        ->join('b.etudiant', 'e')
                        ->where('e.id = :user')
                        ->andWhere('t.id NOT IN (:page)')
                        ->setParameters(['user' => $user->getId(), 'page' => $page->getTrace()->toArray()]);

                },
                'choice_label' => 'titre',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Traces',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Ajouter',
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ])
            ->getForm();

//        dump($page);
//        die();

        $trace = $form->get('trace')->getData();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Si il n'y a pas de trace sélectionnée dans le formulaire
            if ($trace->isEmpty()) {
                $this->addFlash('danger', 'Veuillez sélectionner au moins une trace.');
            } else {
                // Récupérer les pages sélectionnées
                $traces = $form->get('trace')->getData();
                foreach ($traces as $trace) {
                    // Ajouter la trace aux pages sélectionnées
                    $page->addTrace($trace);
//                    $trace->addPage($page);
                }
                //TODO: ajouter à la db les traces qui ne font déjà partie de la page
                foreach ($existingTraces as $existingTrace) {
                    $page->addTrace($existingTrace);
//                    $trace->addPage($page);
                }
//                  dump($page->getTrace());
//                die();
//                dump($existingTraces);
//                  die();

                $pageRepository->save($page, true);

                $this->addFlash('success', 'La trace a été ajoutée à la page avec succès.');
                return $this->redirectToRoute('app_page');
            }
        }
        return $this->render('page/edit.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
        ]);
    }
}
