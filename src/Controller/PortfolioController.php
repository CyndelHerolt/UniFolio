<?php

namespace App\Controller;

use App\Entity\OrdrePage;
use App\Entity\Page;
use App\Entity\Portfolio;
use App\Form\PortfolioType;
use App\Repository\OrdrePageRepository;
use App\Repository\OrdreTraceRepository;
use App\Repository\PageRepository;
use App\Repository\PortfolioRepository;
use App\Repository\TraceRepository;
use App\Repository\ValidationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Service\Attribute\Required;

#[Route('etudiant/bibliotheque')]
class PortfolioController extends AbstractController
{
    public function __construct(
        #[Required] public Security $security
    )
    {
    }

    #[Route('/portfolio', name: 'app_portfolio')]
    public function index(
        PortfolioRepository $portfolioRepository,
    ): Response
    {

        $this->denyAccessUnlessGranted(
            'ROLE_ETUDIANT'
        );

        //Récupérer les portfolios de l'utilisateur connecté
        $etudiant = $this->security->getUser()->getEtudiant();
        $portfolios = $portfolioRepository->findBy(['etudiant' => $etudiant]);

        return $this->render('portfolio/index.html.twig', [
            'portfolios' => $portfolios,
        ]);
    }

    #[Route('/portfolio/show', name: 'app_portfolio_index')]
    public function indexShow(
        Request             $request,
        PortfolioRepository $portfolioRepository,
    ): Response
    {
        $id = $request->query->get('id');

        $etudiant = $this->security->getUser()->getEtudiant();
        $portfolio = $portfolioRepository->findOneBy(['id' => $id]);

        return $this->render('portfolio/show.html.twig', [
            'step' => 'portfolio',
            'id' => $id,
            'portfolio' => $portfolio,
            'etudiant' => $etudiant,
        ]);
    }

    #[Route('/portfolio/show/{id}', name: 'app_portfolio_show')]
    public function show(
        PortfolioRepository  $portfolioRepository,
        OrdrePageRepository  $ordrePageRepository,
        PageRepository       $pageRepository,
        OrdreTraceRepository $ordreTraceRepository,
        TraceRepository      $traceRepository,
        ValidationRepository $validationRepository,
        Request              $request,
                             $id
    ): Response
    {

        $this->denyAccessUnlessGranted(
            'ROLE_ETUDIANT'
        );

        $step = $request->query->get('step', 'portfolio');

        //Récupérer le portfolio de l'utilisateur connecté
        $etudiant = $this->security->getUser()->getEtudiant();
        $portfolio = $portfolioRepository->findOneBy(['etudiant' => $etudiant, 'id' => $id]);


        switch ($step) {

            case 'portfolio' :
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

                $page = $pageRepository->findOneBy(['id' => $request->query->get('page')]);

                $ordreTraces = $ordreTraceRepository->findBy(['page' => $page], ['ordre' => 'ASC']);
                $traces = [];
                foreach ($ordreTraces as $ordreTrace) {
                    $traces[] = $ordreTrace->getTrace();
                }

                break;

        }

        return $this->render('portfolio/_step.html.twig', [
            'step' => $step ?? null,
            'etudiant' => $etudiant ?? null,
            'portfolio' => $portfolio ?? null,
            'pages' => $pages ?? null,
            'traces' => $traces ?? null,
            'validations' => $validations ?? null,
            'competences' => $competences ?? null,
            'page' => $page ?? null,
        ]);
    }

    #[Route('/portfolio/new', name: 'app_portfolio_new')]
    public function new(
        Request             $request,
        PortfolioRepository $portfolioRepository,
        Security            $security
    ): Response
    {

        $this->denyAccessUnlessGranted(
            'ROLE_ETUDIANT'
        );

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
                    //Déplacer le fichier dans le dossier déclaré sous le nom files_directory dans services.yaml
                    $imageFile->move('files_directory', $imageFileName);
//                //Sauvegarder le contenu dans la base de données
                    $portfolio->setBanniere('files_directory' . '/' . $imageFileName);
                } elseif (!in_array($imageFile->guessExtension(), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {
                    $this->addFlash('danger', 'L\'image doit être au format jpg, jpeg, png, gif, svg ou webp');
                }
            } else {
                $portfolio->setBanniere('files_directory/banniere.jpg');
            }

            if ($form->get('visibilite')->getData() === true) {
                $portfolios = $portfolioRepository->findBy(['annee' => $portfolio->getAnnee()]);
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

        return $this->render('portfolio/formPortfolio.html.twig', [
            'form' => $form->createView(),
            'portfolio' => $portfolio,
        ]);
    }

    #[Route('/portfolio/delete/{id}', name: 'app_portfolio_delete')]
    public function delete(
        PortfolioRepository $portfolioRepository,
        int                 $id
    ): Response
    {

        $this->denyAccessUnlessGranted(
            'ROLE_ETUDIANT'
        );


        $portfolio = $portfolioRepository->findOneBy(['id' => $id]);

        $portfolioRepository->remove($portfolio, true);
        $document = $portfolio->getBanniere();
        if ($document !== 'files_directory/banniere.jpg') {
            unlink($document);
        }
        $this->addFlash('success', 'Le Portfolio a été supprimé avec succès');
        return $this->redirectToRoute('app_portfolio');
    }
}
