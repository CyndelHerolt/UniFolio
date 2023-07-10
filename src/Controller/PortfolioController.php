<?php

namespace App\Controller;

use App\Entity\Page;
use App\Entity\Portfolio;
use App\Form\PortfolioType;
use App\Repository\PageRepository;
use App\Repository\PortfolioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Service\Attribute\Required;

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

            if ($form->get('visibilite')->getData() === 'public') {
                $portfolio->setVisibilite(true);
            } elseif ($form->get('visibilite')->getData() === 'prive') {
                $portfolio->setVisibilite(false);
            }

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
