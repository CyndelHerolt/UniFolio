<?php

namespace App\Controller;

use App\Controller\SynchroIntranet\UserSynchro;
use App\Entity\Bibliotheque;
use App\Entity\Enseignant;
use App\Entity\Etudiant;
use App\Entity\Users;
use App\Form\UsersType;
use App\Repository\BibliothequeRepository;
use App\Repository\DepartementRepository;
use App\Repository\EnseignantRepository;
use App\Repository\EtudiantRepository;
use App\Repository\GroupeRepository;
use App\Repository\UsersRepository;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

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
        Request                     $request,
        UsersRepository             $usersRepository,
        UserPasswordHasherInterface $passwordHasher,
        EtudiantRepository          $etudiantRepository,
        BibliothequeRepository      $bibliothequeRepository,
        EnseignantRepository        $enseignantRepository,
        UserSynchro                 $userSynchro,
        HttpClientInterface         $client,
        GroupeRepository            $groupeRepository,
        DepartementRepository       $departementRepository,
        MailerService               $mailerService,
        VerifyEmailHelperInterface  $verifyEmailHelper,
    ): Response
    {

        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plaintextPassword = $user->getPassword();
            $login = $user->getUsername();

//             hash the password (based on the security.yaml config for the $user class)
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);

            if ($etudiantRepository->findOneBy(['username' => $login])) {
                $this->addFlash('danger', 'Vous avez déjà un compte.');
            } elseif ($enseignantRepository->findOneBy(['username' => $login])) {
                $this->addFlash('danger', 'Vous avez déjà un compte.');
            } else {
                $CheckEmailEtudiant = $userSynchro->CheckEmailEtudiant($login, $client, $mailerService, $verifyEmailHelper);
                $getEmailEnseignant = $userSynchro->getEmailEnseignant($login, $client, $mailerService, $verifyEmailHelper);
                if ($CheckEmailEtudiant) {
                    $user->setRoles(['ROLE_ETUDIANT']);
                    $mailEtudiant = $userSynchro->getEmailEtudiant($login, $client);
                    $user->setEmail($mailEtudiant);
                    $usersRepository->save($user, true);
                    $this->addFlash('success', 'Un mail de vérification vous a été envoyé. Veuillez cliquer sur le lien pour valider votre compte.');
                } elseif ($getEmailEnseignant) {
                    $user->setRoles(['ROLE_ENSEIGNANT']);
                    $usersRepository->save($user, true);
                    $this->addFlash('success', 'Un mail de vérification vous a été envoyé. Veuillez cliquer sur le lien pour valider votre compte.');
                } else {
                    $this->addFlash('danger', 'Une erreur s\'est produite, si le problème persiste, veuillez contacter l\'administrateur du site.');
                    return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
                }
            }

            return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('users/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/verify', name: 'app_verify_email')]
    public function verifyUserEmail(
        Request                    $request,
        VerifyEmailHelperInterface $verifyEmailHelper,
        UsersRepository            $usersRepository,
        UserSynchro                $userSynchro,
        HttpClientInterface        $client,
        EtudiantRepository         $etudiantRepository,
        BibliothequeRepository     $bibliothequeRepository,
        EnseignantRepository       $enseignantRepository,
        GroupeRepository           $groupeRepository,
        DepartementRepository      $departementRepository,
    ): Response
    {
        //TODO: synchro intranet
        $user = $usersRepository->findOneBy(['username' => $request->query->get('id')]);
        $login = $user->getUsername();
        if (!$user) {
            throw $this->createNotFoundException();
        }
        try {
            $verifyEmailHelper->validateEmailConfirmation(
                $request->getUri(),
                $user->getUsername(),
                $user->getEmail(),
            );
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('error', $e->getReason());
            return $this->redirectToRoute('app_users_new');
        }
//        dd('TODO');
        $etudiantSynchro = $userSynchro->synchroEtudiant($login, $user, $client, $etudiantRepository, $bibliothequeRepository, $groupeRepository);
        $enseignantSynchro = $userSynchro->synchroEnseignant($login, $user, $client, $enseignantRepository, $departementRepository);
        // Si $etudiantSynchro est true alors on ajoute l'utilisateur dans la base de données
        if ($etudiantSynchro) {
//            $user->setRoles(['ROLE_ETUDIANT']);
//            $usersRepository->save($user, true);
            $this->addFlash('success', 'Votre compte est vérifié, vos informations ont été mises à jour. Vous pouvez vous connecter.');
        } elseif ($enseignantSynchro) {
            $user->setRoles(['ROLE_ENSEIGNANT']);
            $usersRepository->save($user, true);
        }
        return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);

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
