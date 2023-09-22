<?php

namespace App\Controller\SynchroIntranet;

use App\Entity\Bibliotheque;
use App\Entity\EnseignantDepartement;
use App\Entity\Etudiant;
use App\Entity\Enseignant;
use App\Entity\Users;
use App\Repository\BibliothequeRepository;
use App\Repository\DepartementRepository;
use App\Repository\EnseignantRepository;
use App\Repository\EtudiantRepository;
use App\Repository\GroupeRepository;
use App\Repository\SemestreRepository;
use App\Repository\UsersRepository;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class UserSynchro extends AbstractController
{

    #[Route('/api/intranet/etudiant', name: 'app_email_intranet_etudiant')]
    public function CheckEmailEtudiant(
        $login,
        HttpClientInterface $client,
        MailerService $mailerService,
        VerifyEmailHelperInterface $verifyEmailHelper,
    )
    {

        $response = $client->request(
            'GET',
            'https://intranetv3.iut-troyes.univ-reims.fr/fr/api/unifolio/etudiant',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'x-api-key' => $this->getParameter('api_key')
                ],
                    'query' => [
                        'username' => $login
                    ]
            ]
        );

        $response = $response->toArray();

            if (in_array($login, array_column($response, 'username'))) {
                //Sélectionner l'etudiant dans le tableau
                $etudiant = array_filter($response, function ($etudiant) use ($login) {
                    return $etudiant['username'] === $login;
                });
                foreach ($etudiant as $data) {
                    $mailEtudiant = $data['mail_univ'];
                    $signatureComponents = $verifyEmailHelper->generateSignature(
                        'app_verify_email',
                        $data['username'],
                        $data['mail_univ'],
                        ['id' => $data['username']]
                    );
                    $mailerService->sendMail(
                        $mailEtudiant,
                        'Vérification de compte UniFolio',
                        'Afin de vérifier votre compte, merci de cliquer sur le lien suivant. Si vous n\'êtes pas à l\'origine de cette demande, merci de ne pas cliquer sur le lien et de contacter l\'administrateur du site. <br> <a href="' . $signatureComponents->getSignedUrl() . '">Activation du compte UniFolio</a> <br> '
                    );
                }
                return true;
            }
            return false;
    }

    #[Route('/api/intranet/etudiant', name: 'app_get_email_intranet_etudiant')]
    public function getEmailEtudiant(
        $login,
        HttpClientInterface $client,
    )
    {

        $response = $client->request(
            'GET',
            'https://intranetv3.iut-troyes.univ-reims.fr/fr/api/unifolio/etudiant',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'x-api-key' => $this->getParameter('api_key')
                ],
                'query' => [
                    'username' => $login
                ]
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
                    $mailEtudiant = $data['mail_univ'];
                    }
                return $mailEtudiant;
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
        SemestreRepository $semestreRepository,
    )
    {

        $response = $client->request(
            'GET',
            'https://intranetv3.iut-troyes.univ-reims.fr/fr/api/unifolio/etudiant',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'x-api-key' => $this->getParameter('api_key')
                ],
                'query' => [
                    'username' => $login
                ]
            ]
        );

        $response = $response->toArray();

            if (in_array($login, array_column($response, 'username'))) {
                //Sélectionner l'etudiant dans le tableau
                $etudiant = array_filter($response, function ($etudiant) use ($login) {
                    return $etudiant['username'] === $login;
                });
                foreach ($etudiant as $data) {
                    $semestre = $semestreRepository->findOneBy(['libelle' => $data['semestre']]);
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
                    $newEtudiant->setSemestre($semestre);
//                    dd($data['groupes']);
                    foreach ($data['groupes'] as $groupe) {
                        $groupe = $groupeRepository->findOneBy(['id' => $groupe]);
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
    public function checkEmailEnseignant(
        $login,
        HttpClientInterface $client,
        MailerService $mailerService,
        VerifyEmailHelperInterface $verifyEmailHelper
    )
    {

        $response = $client->request(
            'GET',
            'https://intranetv3.iut-troyes.univ-reims.fr/fr/api/unifolio/enseignant',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'x-api-key' => $this->getParameter('api_key')
                ],
                'query' => [
                    'username' => $login
                ]
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
                    $data['username'],
                    $data['mail_univ'],
                    ['id' => $data['username']]
                );
                $mailerService->sendMail($mailEnseignant, 'Vérification de compte UniFolio', 'Afin de vérifier votre compte, merci de cliquer sur le lien suivant. Si vous n\'êtes pas à l\'origine de cette demande, merci de ne pas cliquer sur le lien et de contacter l\'administrateur du site. <br> <a href="' . $signatureComponents->getSignedUrl() . '">Activation du compte UniFolio</a> <br> ');
            }
            return true;
        }
        return false;
    }

    #[Route('/api/intranet/enseignant', name: 'app_get_email_intranet_enseignant')]
    public function getEmailEnseignant(
        $login,
        HttpClientInterface $client,
    )
    {

        $response = $client->request(
            'GET',
            'https://intranetv3.iut-troyes.univ-reims.fr/fr/api/unifolio/enseignant',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'x-api-key' => $this->getParameter('api_key')
                ],
                'query' => [
                    'username' => $login
                ]
            ]
        );

        $response = $response->toArray();

        if (in_array($login, array_column($response, 'username'))) {
            //Sélectionner l'etudiant dans le tableau
            $enseignant = array_filter($response, function ($enseignant) use ($login) {
                return $enseignant['username'] === $login;
            });
            foreach ($enseignant as $data) {
                // Créer un nouvel etudiant dans la base de données avec les données de $etudiant
                $mailEnseignant = $data['mail_univ'];
            }
            return $mailEnseignant;
        }
        return false;
    }


    #[Route('/api/intranet/enseignant', name: 'app_synchro_intranet_enseignant')]
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
            'https://intranetv3.iut-troyes.univ-reims.fr/fr/api/unifolio/enseignant',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'x-api-key' => $this->getParameter('api_key')
                ],
                'query' => [
                    'username' => $login
                ]
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
                $newEnseignant->setUsername($data['username']);
                $newEnseignant->setMailUniv($data['mail_univ']);
                $newEnseignant->setMailPerso($data['mail_perso']);
                $newEnseignant->setTelephone($data['telephone']);
                foreach ($data['departements'] as $departement) {
                    $departement = $departementRepository->findOneBy(['libelle' => $departement]);
                    if ($departement) {
                        $newEnseignantDepartement = new EnseignantDepartement($newEnseignant, $departement);
                        $newEnseignant->AddEnseignantDepartement($newEnseignantDepartement);
                    }
                }
                $enseignantRepository->save($newEnseignant, true);
            }
            return true;
        } else {
            return false;
        }

    }
}
