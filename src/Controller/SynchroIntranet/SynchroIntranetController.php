<?php

namespace App\Controller\SynchroIntranet;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SynchroIntranetController extends AbstractController
{
    #[Route('/test/requete', name: 'app_synchro_intranet')]
    public function index(HttpClientInterface $client): Response
    {
        //https://symfony.com/doc/current/http_client.html

        //todo: il faut modifier services.yaml pour ajouter la clé API récupérée depuis le fichier .env.local
        //todo: en local il faut désactiver la vérification du certificat SSL => framewrok.yaml

        $response = $client->request(
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

        dump($response->getContent());
        die();

        return $this->redirectToRoute('app_dashboard', [
            'reponses' => $response->toArray()
        ]);
    }
}
