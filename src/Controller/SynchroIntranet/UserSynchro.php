<?php

namespace App\Controller\SynchroIntranet;

use App\Entity\Bibliotheque;
use App\Entity\Etudiant;
use App\Entity\Enseignant;
use App\Entity\Users;
use App\Repository\BibliothequeRepository;
use App\Repository\EtudiantRepository;
use App\Repository\GroupeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UserSynchro extends AbstractController
{

    #[Route('/api/intranet/etudiant', name: 'app_synchro_intranet_etudiant')]
    public function synchroEtudiant (
        $mailEtudiant,
        $user,
        HttpClientInterface   $client,
        EtudiantRepository $etudiantRepository,
        BibliothequeRepository $bibliothequeRepository,
        GroupeRepository $groupeRepository
    )
    {

        $response = $client->request(
            'GET',
            'https://127.0.0.1:8001/fr/api/unifolio/etudiant',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]
        );

        $response = $response->toArray();

//        dd($mailEtudiant);

        if (in_array($mailEtudiant, array_column($response, 'mail_univ'))) {
           //Sélectionner l'etudiant dans le tableau
            $etudiant = array_filter($response, function ($etudiant) use ($mailEtudiant) {
                return $etudiant['mail_univ'] === $mailEtudiant;
            });
            foreach ($etudiant as $data) {
            // Créer un nouvel etudiant dans la base de données avec les données de $etudiant
            $newEtudiant = new Etudiant();
            $newEtudiant->setUsers($user);
            $biblio = new Bibliotheque();
            $biblio->setEtudiant($newEtudiant);
            $newEtudiant->setNom($data['nom']);
            $newEtudiant->setPrenom($data['prenom']);
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
        }
        else {
            return false;
        }

    }
}