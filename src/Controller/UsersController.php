<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Controller;

use App\Controller\SynchroIntranet\UserSynchro;
use App\Entity\Bibliotheque;
use App\Entity\Enseignant;
use App\Entity\Etudiant;
use App\Entity\Users;
use App\EventSubscriber\CheckVerifiedUserSubscriber;
use App\Form\UsersType;
use App\Repository\BibliothequeRepository;
use App\Repository\DepartementRepository;
use App\Repository\EnseignantRepository;
use App\Repository\EtudiantRepository;
use App\Repository\GroupeRepository;
use App\Repository\SemestreRepository;
use App\Repository\UsersRepository;
use App\Service\MailerService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
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
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render('users/index.html.twig', [
                'users' => $usersRepository->findAll(),
            ]);
        } else {
            return $this->render('security/accessDenied.html.twig');
        }
    }

    #[Route('/inscription', name: 'app_users_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        UsersRepository $usersRepository,
        UserPasswordHasherInterface $passwordHasher,
        EtudiantRepository $etudiantRepository,
        EnseignantRepository $enseignantRepository,
        UserSynchro $userSynchro,
        HttpClientInterface $client,
        MailerInterface $mailer,
        VerifyEmailHelperInterface $verifyEmailHelper,
    ): Response {

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
                $checkEmailEtudiant = $userSynchro->CheckEmailEtudiant($login, $client, $mailer, $verifyEmailHelper);
                $checkEmailEnseignant = $userSynchro->checkEmailEnseignant($login, $client, $mailer, $verifyEmailHelper);
                if ($checkEmailEtudiant) {
                    $user->setRoles(['ROLE_ETUDIANT']);
                    $mailEtudiant = $userSynchro->getEmailEtudiant($login, $client);
                    $user->setEmail($mailEtudiant);
                    $usersRepository->save($user, true);
                    $this->addFlash('success', 'Un mail de vérification vous a été envoyé. Veuillez cliquer sur le lien pour valider votre compte.');
                } elseif ($checkEmailEnseignant) {
                    $user->setRoles(['ROLE_ENSEIGNANT']);
                    $mailEnseignant = $userSynchro->getEmailEnseignant($login, $client);
                    $user->setEmail($mailEnseignant);
                    $usersRepository->save($user, true);
                    $this->addFlash('success', 'Un mail de vérification vous a été envoyé. Veuillez cliquer sur le lien pour valider votre compte.');
                } else {
//                    $this->addFlash('danger', 'Une erreur s\'est produite, si le problème persiste, veuillez contacter l\'administrateur du site.');
                    $this->addFlash('danger', 'Une erreur s\'est produite, veuillez vérifier que votre login URCA est correct. Si le problème persiste, veuillez contacter l\'administrateur du site.');
                    return $this->redirectToRoute('app_users_new', [], Response::HTTP_SEE_OTHER);
                }
            }

            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('users/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/verify', name: 'app_verify_email')]
    public function verifyUserEmail(
        Request $request,
        VerifyEmailHelperInterface $verifyEmailHelper,
        UsersRepository $usersRepository,
        UserSynchro $userSynchro,
        HttpClientInterface $client,
        EtudiantRepository $etudiantRepository,
        BibliothequeRepository $bibliothequeRepository,
        EnseignantRepository $enseignantRepository,
        GroupeRepository $groupeRepository,
        DepartementRepository $departementRepository,
        SemestreRepository $semestreRepository,
    ): Response {
        $user = $usersRepository->findOneBy(['username' => $request->query->get('id')]);
        $login = $user->getUsername();
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
        $etudiantSynchro = $userSynchro->synchroEtudiant($login, $user, $client, $etudiantRepository, $bibliothequeRepository, $groupeRepository, $semestreRepository);
        $enseignantSynchro = $userSynchro->synchroEnseignant($login, $user, $client, $enseignantRepository, $departementRepository);
        if ($etudiantSynchro) {
            $user->setIsVerified(true);
            $this->addFlash('success', 'Votre compte est vérifié, vos informations ont été mises à jour. Vous pouvez vous connecter.');
        } elseif ($enseignantSynchro) {
            $user->setIsVerified(true);
            $this->addFlash('success', 'Votre compte est vérifié, vos informations ont été mises à jour. Vous pouvez vous connecter.');
        }
        $usersRepository->save($user, true);
        return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/verify/resend', name: 'app_verify_resend_email')]
    public function resendVerifyEmail(
        Request $request,
        VerifyEmailHelperInterface $verifyEmailHelper,
        UsersRepository $usersRepository,
        MailerInterface $mailer
    ) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // récupérer les données du formulaire
            $mail = $request->request->get('mail-univ');

            $user = $usersRepository->findOneBy(['email' => $mail]);

            if ($user === null) {
                $this->addFlash('danger', 'Aucun compte n\'est associé à cette adresse mail. Si le problème persiste, veuillez contacter l\'administrateur du site.');
                return $this->redirectToRoute('app_verify_resend_email');
            }

            if ($user->getEtudiant()) {
                $userInfos = $user->getEtudiant();
            } elseif ($user->getEnseignant()) {
                $userInfos = $user->getEnseignant();
            } else {
                $userInfos = null;
            }

            if ($user && $user->getIsVerified() === false) {
                $signatureComponents = $verifyEmailHelper->generateSignature(
                    'app_verify_email',
                    $user->getUsername(),
                    $user->getEmail(),
                    ['id' => $user->getUsername()]
                );

                $email = (new TemplatedEmail())
                    ->from(new Address('portfolio.iut-troyes@univ-reims.fr', 'UniFolio Mail Bot'))
                    ->to($user->getEmail())
                    ->subject('UniFolio - Vérification de compte - Action Requise')
                    ->htmlTemplate('email.html.twig')
                    ->context([
                        'confirm_link' => $signatureComponents->getSignedUrl(),
                        'user' => $userInfos,
                        'email_subject' => 'Vérification de votre compte',
                        'email_message' => '<p>Pour accéder à votre compte, il est impératif de vérifier votre adresse mail. Cela garantit la sécurité de vos données personnelles.</p>
                                    <p>Pour ce faire, cliquez sur le bouton ci-dessous.</p>
                                    <p>Si vous n\'êtes pas à l\'origine de cette demande, que vous rencontrez des difficultés ou que vous avez des questions, merci de ne pas cliquer sur le bouton et de nous contacter à portfolio.iut-troyes@univ-reims.fr.</p>
                                    <p>Cordialement,</p>',
                        'email_button' => 'confirm_email'
                    ]);
                $mailer->send($email);

                $this->addFlash('success', 'Un nouveau mail de vérification vous a été envoyé. Veuillez cliquer sur le lien pour valider votre compte.');
            } elseif (!$user) {
                $this->addFlash('danger', 'Aucun compte n\'est associé à cette adresse mail.');
            } elseif ($user->getIsVerified() === true) {
                $this->addFlash('danger', 'Votre compte est déjà vérifié. Si vous ne parvenez toujours pas à vous connecter, veuillez contacter l\'administrateur du site.');
            }
        }

        return $this->render('users/resend_verify_email.html.twig', [
        ]);
    }
}
