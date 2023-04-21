<?php

namespace App\Controller\SynchroIntranet;

use App\Entity\Bibliotheque;
use App\Entity\Etudiant;
use App\Entity\Enseignant;
use App\Entity\Users;
use App\Repository\BibliothequeRepository;
use App\Repository\DepartementRepository;
use App\Repository\EnseignantRepository;
use App\Repository\EtudiantRepository;
use App\Repository\GroupeRepository;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class UserSynchro extends AbstractController
{

    #[Route('/api/intranet/etudiant', name: 'app_email_intranet_etudiant')]
    public function getEmailEtudiant(
        $login,
        HttpClientInterface $client,
        MailerService $mailerService,
        VerifyEmailHelperInterface $verifyEmailHelper
    )
    {

        $response = $client->request(
            'GET',
            'https://127.0.0.1:8001/fr/api/unifolio/etudiant',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ],
            ]
        );

        $response = $response->toArray();

            if (in_array($login, array_column($response, 'username'))) {
                //Sélectionner l'etudiant dans le tableau
                $etudiant = array_filter($response, function ($etudiant) use ($login) {
                    return $etudiant['username'] === $login;
                });
                foreach ($etudiant as $data) {
//                    dd($data);
                    // Créer un nouvel etudiant dans la base de données avec les données de $etudiant
                    $mailEtudiant = $data['mail_univ'];
                    $signatureComponents = $verifyEmailHelper->generateSignature(
                        'app_verify_email',
                        $data['id'],
                        $data['mail_univ'],
                        ['id' => $data['id']]
                    );
                    $mailerService->sendMail($mailEtudiant, 'Vérification de compte UniFolio', 'Afin de vérifier votre compte, merci de cliquer sur le lien suivant : ' . $signatureComponents->getSignedUrl());
                }
                return true;
            }
            return false;
    }

    #[Route('/api/intranet/etudiant', name: 'app_synchro_intranet_etudiant')]
    public function synchroEtudiant(
        $login,
        $user,
        HttpClientInterface $client,
        EtudiantRepository $etudiantRepository,
        BibliothequeRepository $bibliothequeRepository,
        GroupeRepository $groupeRepository,
    )
    {

        $response = $client->request(
            'GET',
            'https://127.0.0.1:8001/fr/api/unifolio/etudiant',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ],
            ]
        );

        $response = $response->toArray();

            if (in_array($login, array_column($response, 'username'))) {
                //Sélectionner l'etudiant dans le tableau
                $etudiant = array_filter($response, function ($etudiant) use ($login) {
                    return $etudiant['username'] === $login;
                });
                foreach ($etudiant as $data) {
                    // Créer un nouvel etudiant dans la base de données avec les données de $etudiant
                    $newEtudiant = new Etudiant();
                    $newEtudiant->setUsers($user);
                    $biblio = new Bibliotheque();
                    $biblio->setEtudiant($newEtudiant);
                    $newEtudiant->setNom($data['nom']);
                    $newEtudiant->setPrenom($data['prenom']);
                    $newEtudiant->setUsername($data['username']);
                    $newEtudiant->setMailUniv($data['mail_univ']);
                    $newEtudiant->setMailPerso($data['mail_perso']);
                    $newEtudiant->setTelephone($data['telephone']);
                    foreach ($data['groupes'] as $groupe) {
                        $groupe = $groupeRepository->findOneBy(['libelle' => $groupe]);
                        $newEtudiant->addGroupe($groupe);
                    }
                    $etudiantRepository->save($newEtudiant, true);
                    $bibliothequeRepository->save($biblio, true);
                }
                return true;
            } else {
                return false;
            }
        }

    #[Route('/api/intranet/enseignant', name: 'app_email_intranet_enseignant')]
    public function getEmailEnseignant(
        $login,
        HttpClientInterface $client,
        MailerService $mailerService,
        VerifyEmailHelperInterface $verifyEmailHelper
    )
    {

        $response = $client->request(
            'GET',
            'https://127.0.0.1:8001/fr/api/unifolio/enseignant',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ],
            ]
        );

        $response = $response->toArray();

        if (in_array($login, array_column($response, 'username'))) {
            //Sélectionner l'enseignant dans le tableau
            $enseignant = array_filter($response, function ($enseignant) use ($login) {
                return $enseignant['username'] === $login;
            });
            foreach ($enseignant as $data) {
                $mailEnseignant = $data['mail_univ'];
                $signatureComponents = $verifyEmailHelper->generateSignature(
                    'app_verify_email',
                    $data['id'],
                    $data['mail_univ'],
                    ['id' => $data['id']]
                );
                $mailerService->sendMail($mailEnseignant, 'Vérification de compte UniFolio', 'Afin de vérifier votre compte, merci de cliquer sur le lien suivant : ' . $signatureComponents->getSignedUrl());
            }
            return true;
        }
        return false;
    }


    #[Route('/api/intranet/personnel', name: 'app_synchro_intranet_personnel')]
    public function synchroEnseignant(
        $login,
        $user,
        HttpClientInterface $client,
        EnseignantRepository $enseignantRepository,
        DepartementRepository $departementRepository,
    )
    {

        $response = $client->request(
            'GET',
            'https://127.0.0.1:8001/fr/api/unifolio/enseignant',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ],
            ]
        );

        $response = $response->toArray();

        if (in_array($login, array_column($response, 'username'))) {
            //Sélectionner l'enseignant dans le tableau
            $enseignant = array_filter($response, function ($enseignant) use ($login) {
                return $enseignant['username'] === $login;
            });
            foreach ($enseignant as $data) {
                // Créer un nouvel enseignant dans la base de données avec les données de $enseignant
                $newEnseignant = new Enseignant();
                $newEnseignant->setUsers($user);
                $newEnseignant->setNom($data['nom']);
                $newEnseignant->setPrenom($data['prenom']);
                $newEnseignant->setMailUniv($data['mail_univ']);
                $newEnseignant->setMailPerso($data['mail_perso']);
                $newEnseignant->setTelephone($data['telephone']);
                foreach ($data['departements'] as $departement) {
                    $departement = $departementRepository->findOneBy(['libelle' => $departement]);
                    $newEnseignant->AddDepartement($departement);
                }
                $enseignantRepository->save($newEnseignant, true);
            }
            return true;
        } else {
            return false;
        }

    }
}