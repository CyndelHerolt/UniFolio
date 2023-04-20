<?php

namespace App\Controller;

use App\Controller\SynchroIntranet\UserSynchro;
use App\Entity\Bibliotheque;
use App\Entity\Enseignant;
use App\Entity\Etudiant;
use App\Entity\Users;
use App\Form\UsersType;
use App\Repository\BibliothequeRepository;
use App\Repository\EnseignantRepository;
use App\Repository\EtudiantRepository;
use App\Repository\GroupeRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/users')]
class UsersController extends AbstractController
{
    #[Route('/', name: 'app_users_index', methods: ['GET'])]
    public function index(UsersRepository $usersRepository): Response
    {
        return $this->render('users/index.html.twig', [
            'users' => $usersRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_users_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        UsersRepository $usersRepository,
        UserPasswordHasherInterface $passwordHasher,
        EtudiantRepository $etudiantRepository,
        BibliothequeRepository $bibliothequeRepository,
        EnseignantRepository $enseignantRepository,
        UserSynchro $userSynchro,
        HttpClientInterface   $client,
        GroupeRepository $groupeRepository,
    ): Response
    {

        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plaintextPassword = $user->getPassword();
            var_dump($plaintextPassword);

//             hash the password (based on the security.yaml config for the $user class)
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);

            if (str_contains($user->getUsername(), '@etudiant.univ-reims.fr')) {
                $mailEtudiant = $user->getUsername();
                $etudiantSynchro = $userSynchro->synchroEtudiant($mailEtudiant, $user, $client, $etudiantRepository, $bibliothequeRepository, $groupeRepository);
                // Si $etuSynchro est true alors on ajoute l'utilisateur dans la base de données
                if ($etudiantSynchro) {
                    $user->setRoles(['ROLE_ETUDIANT']);
                    $usersRepository->save($user, true);
//                    dd($user);
                }

                else {
                    dd('Erreur lors de la synchro');
                }
            }
//            elseif (str_contains($user->getEmail(), '@univ-reims.fr')) {
//                $checkEnseignant = $userSynchro->synchroEnseignant($user->getEmail());
//            }

//            if (in_array('ROLE_ETUDIANT', $user->getRoles())) {
//                $etudiant = new Etudiant();
//                $etudiant->setUsers($user);
//                $biblio = new Bibliotheque();
//                $biblio->setEtudiant($etudiant);
//                $etudiantRepository->save($etudiant, true);
//                $bibliothequeRepository->save($biblio, true);
//            }
//            elseif (in_array('ROLE_ENSEIGNANT', $user->getRoles())) {
//                $enseignant = new Enseignant();
//                $enseignant->setUsers($user);
//                $enseignantRepository->save($enseignant, true);
//            }

//            $usersRepository->save($user, true);

            return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('users/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_users_show', methods: ['GET'])]
    public function show(Users $user): Response
    {
        return $this->render('users/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_users_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Users $user, UsersRepository $usersRepository, UserPasswordHasherInterface $passwordHasher, EtudiantRepository $etudiantRepository): Response
    {
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plaintextPassword = $user->getPassword();
//            var_dump($plaintextPassword);

//             hash the password (based on the security.yaml config for the $user class)
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);

            $usersRepository->save($user, true);

            return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_users_delete', methods: ['POST'])]
    public function delete(Request $request, Users $user, UsersRepository $usersRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $usersRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
    }
}
