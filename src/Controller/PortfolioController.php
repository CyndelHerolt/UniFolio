<?php
/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Controller;

use App\Classes\DataUserSession;
use App\Entity\Commentaire;
use App\Entity\Portfolio;
use App\Event\CommentaireEvent;
use App\Form\CommentaireType;
use App\Form\PortfolioType;
use App\Repository\CommentaireRepository;
use App\Repository\EnseignantRepository;
use App\Repository\NotificationRepository;
use App\Repository\OrdrePageRepository;
use App\Repository\OrdreTraceRepository;
use App\Repository\PageRepository;
use App\Repository\PortfolioRepository;
use App\Repository\TraceRepository;
use App\Repository\ValidationRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Service\Attribute\Required;

class PortfolioController extends BaseController
{
    public function __construct(
        #[Required] public Security  $security,
        DataUserSession              $dataUserSession,
        private FormFactoryInterface $formFactory,
    )
    {
    }

    #[Route('etudiant/portfolio', name: 'app_portfolio')]
    public function index(
        PortfolioRepository $portfolioRepository,
    ): Response
    {
        if ($this->isGranted('ROLE_ETUDIANT')) {

            $data_user = $this->dataUserSession;

            //Récupérer les portfolios de l'utilisateur connecté
            $etudiant = $this->security->getUser()->getEtudiant();
            $portfolios = $portfolioRepository->findBy(['etudiant' => $etudiant]);

            return $this->render('portfolio/index.html.twig', [
                'portfolios' => $portfolios,
                'data_user' => $data_user,
            ]);
        } else {
            return $this->render('security/accessDenied.html.twig');
        }
    }

    #[Route('/portfolio/show', name: 'app_portfolio_index')]
    public function indexShow(
        Request                  $request,
        PortfolioRepository      $portfolioRepository,
        CommentaireRepository    $commentaireRepository,
        TraceRepository          $traceRepository,
        NotificationRepository   $notificationRepository,
        EventDispatcherInterface $eventDispatcher
    ): Response
    {
        $data_user = $this->dataUserSession;
        $id = $request->query->get('id');

        $user = $this->security->getUser();
        $portfolio = $portfolioRepository->findOneBy(['id' => $id]);

        $notificationId = $request->query->get('notification_id');

        if ($notificationId) {
            $notification = $notificationRepository->find($notificationId);
            if ($notification && !$notification->isLu()) {
                $notification->setLu(true);
                $notificationRepository->save($notification, true);
            }
        }


        if ($this->security->getUser()->getEnseignant()) {
            $enseignant = $this->security->getUser()->getEnseignant();

            //si un form en post est envoyé
            if ($_POST) {
                $data = $_POST;
                // vérifier que le form est un form de type CommentaireType
                if (isset($data['commentaire'])) {
                    // vérifier qu'aucun champ n'est vide
                    if (empty($data['commentaire']['contenu'])) {
                        $this->addFlash('error', 'Le champ commentaire ne peut pas être vide');
                        return $this->redirectToRoute('enseignant_dashboard');
                    } else {
                        $commentaire = new Commentaire();
                        $commentaire->setContenu(htmlspecialchars(htmlspecialchars($data['commentaire']['contenu'])));
                        $commentaire->setEnseignant($enseignant);
                        $commentaire->setVisibilite($data['commentaire']['visibilite']);
                        $commentaire->setDateCreation(new \DateTime());
                        $commentaire->setDateModification(new \DateTime());
                        if (isset($_POST['traceId'])) {
                            $trace = $traceRepository->find($_POST['traceId']);
                            $commentaire->setTrace($trace);
                        } elseif (isset($_POST['portfolioId'])) {
                            $portfolio = $portfolioRepository->find($_POST['portfolioId']);
                            $commentaire->setPortfolio($portfolio);
                        }

                        $commentaireRepository->save($commentaire, true);

                        if ($commentaire->isVisibilite() == 1) {
                            $event = new CommentaireEvent($commentaire);
                            $eventDispatcher->dispatch($event, CommentaireEvent::COMMENTED);
                        }

                        $this->addFlash('success', 'Commentaire ajouté avec succès !');

                        return $this->redirectToRoute('app_portfolio_index', ['id' => $id]);
                    }
                }
            }
        }

        return $this->render('portfolio/show.html.twig', [
            'step' => 'portfolio',
            'id' => $id,
            'portfolio' => $portfolio,
            'user' => $user,
            'data_user' => $data_user,

        ]);
    }

    #[Route('/portfolio/show/{id}', name: 'app_portfolio_show')]
    public function show(
        PortfolioRepository      $portfolioRepository,
        OrdrePageRepository      $ordrePageRepository,
        PageRepository           $pageRepository,
        OrdreTraceRepository     $ordreTraceRepository,
        Request                  $request,
                                 $id
    ): Response
    {
        $data_user = $this->dataUserSession;
        $step = $request->query->get('step', 'portfolio');

        //Récupérer le portfolio de l'utilisateur connecté
        $user = $this->security->getUser();
        $portfolio = $portfolioRepository->findOneBy(['id' => $id]);


        switch ($step) {

            case 'portfolio' :

                if ($this->isGranted('ROLE_ENSEIGNANT')) {
                    // Créez une instance de votre entité Commentaire
                    $commentaire = new Commentaire();

                    // Créez votre formulaire CommentaireType
                    $this->form = $this->formFactory->create(CommentaireType::class, $commentaire);
                    $this->commentForm = $this->form->createView();
                }
                //Récupérer les pages du portfolio
                $ordrePages = $ordrePageRepository->findBy(['portfolio' => $portfolio], ['ordre' => 'ASC']);
                $pages = [];
                foreach ($ordrePages as $ordrePage) {
                    $pages[] = $ordrePage->getPage();
                }

                $traces = [];
                foreach ($pages as $page) {
                    $ordreTraces = $ordreTraceRepository->findBy(['page' => $page], ['ordre' => 'ASC']);
                    foreach ($ordreTraces as $ordreTrace) {
                        $traces[] = $ordreTrace->getTrace();
                    }
                }

                break;

            case 'page' :

                if ($this->isGranted('ROLE_ETUDIANT')) {

                    $page = $pageRepository->findOneBy(['id' => $request->query->get('page')]);

                    $ordreTraces = $ordreTraceRepository->findBy(['page' => $page], ['ordre' => 'ASC']);
                    $traces = [];
                    foreach ($ordreTraces as $ordreTrace) {
                        $traces[] = $ordreTrace->getTrace();
                    }
                } else {
                    return $this->render('security/accessDenied.html.twig');
                }

                break;

            case 'evalPage' :

                if ($this->isGranted('ROLE_ENSEIGNANT')) {

                    $page = $pageRepository->findOneBy(['id' => $request->query->get('page')]);

                    $ordreTraces = $ordreTraceRepository->findBy(['page' => $page], ['ordre' => 'ASC']);
                    $traces = [];
                    foreach ($ordreTraces as $ordreTrace) {
                        $traces[] = $ordreTrace->getTrace();
                    }
                } else {
                    return $this->render('security/accessDenied.html.twig');
                }

                break;

        }

        return $this->render('portfolio/_step.html.twig', [
            'step' => $step ?? null,
            'user' => $user ?? null,
            'portfolio' => $portfolio ?? null,
            'pages' => $pages ?? null,
            'traces' => $traces ?? null,
            'validations' => $validations ?? null,
            'competences' => $competences ?? null,
            'page' => $page ?? null,
            'commentForm' => $this->commentForm ?? null,
            'data_user' => $data_user ?? null,
        ]);
    }

    #[Route('etudiant/portfolio/new', name: 'app_portfolio_new')]
    public function new(
        Request             $request,
        PortfolioRepository $portfolioRepository,
        Security            $security
    ): Response
    {

        if ($this->isGranted('ROLE_ETUDIANT')) {
            $data_user = $this->dataUserSession;
            $user = $security->getUser()->getEtudiant();
            $annee = $user->getSemestre()->getAnnee();
            $portfolio = new Portfolio();

            $form = $this->createForm(PortfolioType::class, $portfolio, ['user' => $user]);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()
            ) {
                $portfolio->setEtudiant($user);

                $imageFile = $form['banniere']->getData();
                if ($imageFile) {
                    $imageFileName = uniqid() . '.' . $imageFile->guessExtension();
                    //Vérifier si le fichier est au bon format
                    if (in_array($imageFile->guessExtension(), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {
                        //Déplacer le fichier dans le dossier déclaré sous le nom$this->>$this->getParameter('PATH_FILES' . dans services.yaml
                        $imageFile->move($_ENV['PATH_FILES'].'', $imageFileName);
//                //Sauvegarder le contenu dans la base de données
                        $portfolio->setBanniere($_ENV['SRC_FILES'].'/'.$imageFileName);
                    } elseif (!in_array($imageFile->guessExtension(), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {
                        $this->addFlash('danger', 'L\'image doit être au format jpg, jpeg, png, gif, svg ou webp');
                    }
                } else {
                    $portfolio->setBanniere($_ENV['SRC_FILES'].'/banniere.jpg');
                }

                if ($form->get('optSearch')->getData() === true) {
                    $portfolio->setOptSearch(true);
                } elseif ($form->get('optSearch')->getData() === false) {
                    $portfolio->setOptSearch(false);
                }

                if ($form->get('visibilite')->getData() === true) {
                    $portfolios = $portfolioRepository->findBy(['annee' => $annee]);
                    foreach ($portfolios as $otherportfolio) {
                        $otherportfolio->setVisibilite(false);
                        $portfolioRepository->save($otherportfolio, true);
                    }
                    $portfolio->setVisibilite(true);
                } elseif ($form->get('visibilite')->getData() === false) {
                    $portfolio->setVisibilite(false);
                }

                $portfolio->setAnnee($annee);
                $portfolio->setDateModification(new \DateTime('now'));

                $portfolioRepository->save($portfolio, true);

                return $this->redirectToRoute('app_portfolio_process_index', ['id' => $portfolio->getId()]);
            }
        } else {
            return $this->render('security/accessDenied.html.twig');
        }

        return $this->render('portfolio/formPortfolio.html.twig', [
            'form' => $form->createView(),
            'portfolio' => $portfolio,
            'data_user' => $data_user,
        ]);
    }

    #[Route('etudiant/portfolio/delete/{id}', name: 'app_portfolio_delete')]
    public function delete(
        PortfolioRepository $portfolioRepository,
        int                 $id
    ): Response
    {
        if ($this->isGranted('ROLE_ETUDIANT')) {

            $portfolio = $portfolioRepository->findOneBy(['id' => $id]);

            $portfolioRepository->remove($portfolio, true);
            $document = $portfolio->getBanniere();
            if ($document !== $_ENV['SRC_FILES'].'/banniere.jpg') {
                $document = substr($document, strrpos($document, '/') + 1);
                $document = $_ENV['PATH_FILES'] . '/' . $document;

                unlink($document);
            }
            $this->addFlash('success', 'Le Portfolio a été supprimé avec succès');
            return $this->redirectToRoute('app_portfolio');
        } else {
            return $this->render('security/accessDenied.html.twig');
        }
    }

    #[Route('enseignant/portfolio/delete/{id}', name: 'app_delete_commentaire')]
    public function deleteComment(
        Request               $request,
        CommentaireRepository $commentaireRepository
    )
    {
        if ($this->isGranted('ROLE_ENSEIGNANT')) {

            $commentaireId = $request->get('id');
            $commentaire = $commentaireRepository->find($commentaireId);
            $commentaireRepository->remove($commentaire, true);

            $this->addFlash('success', 'Commentaire supprimé avec succès !');

            // Si la requête venait d'une page de portfolio, on redirige vers cette page
            if ($request->headers->get('referer') && strpos($request->headers->get('referer'), 'portfolio/show')) {
                return $this->redirect($request->headers->get('referer'));
            } elseif ($request->headers->get('referer') && strpos($request->headers->get('referer'), 'enseignant/dashboard')) {
                return $this->redirect($request->headers->get('referer'));
            } else {
                return $this->redirectToRoute('enseignant_dashboard');
            }
        } else {
            return $this->render('security/accessDenied.html.twig');
        }
    }
}
