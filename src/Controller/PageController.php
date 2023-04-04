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

        $this->denyAccessUnlessGranted(
            'ROLE_ETUDIANT'
        );

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

        $this->denyAccessUnlessGranted(
            'ROLE_ETUDIANT'
        );

        $user = $this->security->getUser()->getEtudiant();
        $page = new Page();

        $biblio = $this->bibliothequeRepository->findOneBy(['etudiant' => $user]);
        $traces = $this->traceRepository->findBy(['bibliotheque' => $biblio]);

        $form = $this->createForm(PageType::class, $page, ['user' => $user]);

        $trace = $form->get('trace')->getData();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($trace->isEmpty()) {
                $this->addFlash('danger', 'Veuillez sélectionner au moins une trace.');
            } else {

                //Récupérer l'ordre saisi dans le form
                $ordreSaisi = $form->get('ordre')->getData();
//                dd($ordreSaisi);

                $pages = [];
                //Pour chaque trace de la bibliothèque de l'utilisateur
                foreach ($traces as $existingTrace) {
//                    dd($traces);
                    // Récupérer les pages et les regrouper dans un tableau
                    $pages = array_merge($pages, $existingTrace->getPages()->toArray());
                    // Si deux pages sont les mêmes, ne les afficher qu'une seule fois
                    $pages = array_unique($pages, SORT_REGULAR);
//                    dd($pages);
                }
//                    dd($pages);
                //Pour chaque page
                foreach ($pages as $pageStock) {
                    //Récupérer l'ordre de la page
                    $ordre = $pageStock->getOrdre();
//            dd($ordre);
                    //Si l'ordre saisi est égal à l'ordre de la page
                    if ($ordre === $ordreSaisi && $pageStock !== $page) {
                        // Attribuer l'ordre saisi à la page en cours d'édition
                        $page->setOrdre($ordreSaisi);
                        //Attribuer l'ordre qui se trouve en dernière position du tableau de choices à la page en cours de boucle
                        $pageStock->setOrdre(count($pages) + 1);
                    }
                }


                $pageRepository->save($page, true);

                $this->addFlash('success', 'La page a été créée avec succès.');
                return $this->redirectToRoute('app_page');
            }
        }

        return $this->render('page/formPage.html.twig', [
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

        $this->denyAccessUnlessGranted(
            'ROLE_ETUDIANT'
        );

        $user = $security->getUser()->getEtudiant();
        $page = $pageRepository->findOneBy(['id' => $id]);

        $biblio = $this->bibliothequeRepository->findOneBy(['etudiant' => $user]);
        $traces = $this->traceRepository->findBy(['bibliotheque' => $biblio]);
//        dd($traces);

        $form = $this->createForm(PageType::class, $page, ['user' => $user]);

        $trace = $form->get('trace')->getData();
        $ordreOrigine = $page->getOrdre();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Si il n'y a pas de trace sélectionnée dans le formulaire
            if ($trace->isEmpty()) {
                $this->addFlash('danger', 'Veuillez sélectionner au moins une trace.');
            } else {

                //Récupérer l'ordre saisi dans le form
                $ordreSaisi = $form->get('ordre')->getData();
//                dd($ordreSaisi);

                $pages = [];
                //Pour chaque trace de la bibliothèque de l'utilisateur
                foreach ($traces as $existingTrace) {
//                    dd($traces);
                    // Récupérer les pages et les regrouper dans un tableau
                    $pages = array_merge($pages, $existingTrace->getPages()->toArray());
                    // Si deux pages sont les mêmes, ne les afficher qu'une seule fois
                    $pages = array_unique($pages, SORT_REGULAR);
//                    dd($pages);
                }
//                    dd($pages);
                //Pour chaque page
                foreach ($pages as $pageStock) {
                    //Récupérer l'ordre de la page
                    $ordre = $pageStock->getOrdre();
//            dd($ordre);
                    //Si l'ordre saisi est égal à l'ordre de la page
                    if ($ordre === $ordreSaisi && $pageStock !== $page) {
                        // Attribuer l'ordre saisi à la page en cours d'édition
                        $page->setOrdre($ordreSaisi);
                        //Attribuer l'ordre de la page en cours d'édition à la page en cours de boucle
                        $pageStock->setOrdre($ordreOrigine);
                    }
                }

                $pageRepository->save($page, true);

                $this->addFlash('success', 'La page a bien été modifiée.');
                return $this->redirectToRoute('app_page');
            }
        }

        return $this->render('page/formPage.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
        ]);
    }

    #[Route('/page/delete/{id}', name: 'app_page_delete')]
    public function delete(
        Request        $request,
        PageRepository $pageRepository,
        int            $id,
    ): Response
    {

        $this->denyAccessUnlessGranted(
            'ROLE_ETUDIANT'
        );

        $page = $pageRepository->find($id);

        $pageRepository->remove($page, true);
        $this->addFlash('success', 'La page a été supprimée avec succès.');
        return $this->redirectToRoute('app_page');
    }

    #[Route('/page/trace/{id}', name: 'app_add_to_page')]
    public function addTrace(
        Request         $request,
        PageRepository  $pageRepository,
        TraceRepository $traceRepository,
        Security        $security,
        int             $id,
    ): Response
    {

        $this->denyAccessUnlessGranted(
            'ROLE_ETUDIANT'
        );

        $user = $security->getUser()->getEtudiant();
        $page = $pageRepository->findOneBy(['id' => $id]);

        //Récupérer les traces liées à la page dont l'id est passé en paramètre
        $existingTraces = $page->getTrace();


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
                'mapped' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Ajouter',
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ])
            ->getForm();


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Récupérer les id traces sélectionnées dans le formulaire
            $traces = $form->get('trace')->getData();
//            dd($traces);

            //Si il n'y a pas de trace sélectionnée dans le formulaire
            if ($traces->isEmpty()) {
                $this->addFlash('danger', 'Veuillez sélectionner au moins une trace.');
            } else {
                foreach ($traces as $trace) {
                    $trace = $traceRepository->find(['id' => $trace]);
                    // Ajouter la trace aux pages sélectionnées
                    $page->addTrace($trace);
                }
                foreach ($existingTraces as $existingTrace) {
                    $page->addTrace($existingTrace);
                }

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
