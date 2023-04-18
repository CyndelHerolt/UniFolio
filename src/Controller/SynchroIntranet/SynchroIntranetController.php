<?php

namespace App\Controller\SynchroIntranet;

use App\Entity\Departement;
use App\Entity\Semestre;
use App\Repository\DepartementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SynchroIntranetController extends AbstractController
{
    #[Route('/test/requete', name: 'app_synchro_intranet')]
    public function index(
        HttpClientInterface   $client,
        DepartementRepository $departementRepository
    ): Response
    {
        //https://symfony.com/doc/current/http_client.html

        //todo: il faut modifier services.yaml pour ajouter la clé API récupérée depuis le fichier .env.local
        //todo: en local il faut désactiver la vérification du certificat SSL => framewrok.yaml

        $departements = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/departement/liste',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $departements = $departements->toArray();
        foreach ($departements as $departement) {
            $existingDepartement = $departementRepository->findOneBy(['libelle' => $departement['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingDepartement) {
                $existingDepartement->setLibelle($departement['libelle']);
                $existingDepartement->setLogoName($departement['logo']);
                $existingDepartement->setCouleur($departement['couleur']);
                $existingDepartement->setTelContact($departement['tel_contact']);
                $existingDepartement->setDescription($departement['description']);
                $departementRepository->save($existingDepartement, true);
                } else {
                //Sinon, on le crée
                $newDepartement = new Departement();
                $newDepartement->setLibelle($departement['libelle']);
                $newDepartement->setLogoName($departement['logo']);
                $newDepartement->setCouleur($departement['couleur']);
                $newDepartement->setTelContact($departement['tel_contact']);
                $newDepartement->setDescription($departement['description']);
                $departementRepository->save($newDepartement, true);
            }
        }
        $this->addFlash('success', 'Les données ont bien été importées.');


//        dump($response->getContent());
//        die();


        return $this->render('synchro_intranet/index.html.twig', [
            'departements' => $departements
        ]);
    }
}
