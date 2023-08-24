<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use App\Repository\DepartementRepository;
use App\Repository\DiplomeRepository;
use App\Repository\EnseignantRepository;
use App\Repository\GroupeRepository;
use App\Repository\TraceRepository;
use App\Repository\UsersRepository;
use App\Repository\EtudiantRepository;
use App\Repository\AnneeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Service\Attribute\Required;

#[Route('/enseignant')]
class DashboardEnseignantController extends BaseController
{

    #[Route('/dashboard', name: 'enseignant_dashboard')]
    public function index(
        UsersRepository       $usersRepository,
        EnseignantRepository  $enseignantRepository,
        DepartementRepository $departementRepository,
        TraceRepository       $traceRepository,
        CommentaireRepository $commentaireRepository,
    ): Response
    {
        if ($this->isGranted('ROLE_ENSEIGNANT')) {

        $data_user = $this->dataUserSession;

        $usersRepository->findAll();
        $userId = $this->getUser()->getUserIdentifier();
        $enseignant = $enseignantRepository->findOneBy(['username' => $this->getUser()->getUsername()]);
        $departement = $departementRepository->findDepartementEnseignantDefaut($enseignant);

            if ($userId === 'enseignant') {
                foreach ($departement as $dept) {
                    $data_user->setDepartement($dept);
                }
                return $this->render('dashboard_enseignant/index.html.twig', [
                    'admin' => '/admin?_switch_user=_exit',
                    'data_user' => $data_user,
                ]);
            } else {

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
//                            dd($_POST['traceId']);
                            $commentaire = new Commentaire();
                            $commentaire->setContenu(htmlspecialchars($data['commentaire']['contenu']));
                            $commentaire->setEnseignant($enseignant);
                            $commentaire->setVisibilite($data['commentaire']['visibilite']);
                            $commentaire->setDateCreation(new \DateTime());
                            $commentaire->setDateModification(new \DateTime());
                            if ($_POST['traceId']) {
                                $trace = $traceRepository->find($_POST['traceId']);
                                $commentaire->setTrace($trace);
                            }
                            $commentaireRepository->save($commentaire, true);

                            $this->addFlash('success', 'Commentaire ajouté avec succès !');

                            return $this->redirectToRoute('enseignant_dashboard');
                        }
                    }

                }

                return $this->render('dashboard_enseignant/index.html.twig', [
                    'admin' => '',
                    'data_user' => $data_user,
                ]);
            }
        } else {
            return $this->render('security/accessDenied.html.twig');
        }
    }
}
