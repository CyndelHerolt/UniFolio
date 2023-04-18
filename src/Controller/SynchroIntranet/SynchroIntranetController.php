<?php

namespace App\Controller\SynchroIntranet;

use App\Entity\Departement;
use App\Entity\Diplome;
use App\Entity\Semestre;
use App\Repository\DepartementRepository;
use App\Repository\DiplomeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SynchroIntranetController extends AbstractController
{
    #[Route('/test/requete', name: 'app_synchro_intranet')]
    public function index(
        HttpClientInterface   $client,
        DepartementRepository $departementRepository,
        DiplomeRepository     $diplomeRepository,

    ): Response
    {
        //https://symfony.com/doc/current/http_client.html

        //-------------------------------------------------------------------------------------------------------
        //-----------------------------------------DEPARTEMENTS--------------------------------------------------
        //-------------------------------------------------------------------------------------------------------

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
                $existingDepartement->setId($departement['id']);
                $existingDepartement->setLibelle($departement['libelle']);
                $existingDepartement->setLogoName($departement['logo']);
                $existingDepartement->setCouleur($departement['couleur']);
                $existingDepartement->setTelContact($departement['tel_contact']);
                $existingDepartement->setDescription($departement['description']);
                $departementRepository->save($existingDepartement, true);
            } else {
                //Sinon, on le crée
                $newDepartement = new Departement();
                $newDepartement->setId($departement['id']);
                $newDepartement->setLibelle($departement['libelle']);
                $newDepartement->setLogoName($departement['logo']);
                $newDepartement->setCouleur($departement['couleur']);
                $newDepartement->setTelContact($departement['tel_contact']);
                $newDepartement->setDescription($departement['description']);
                $departementRepository->save($newDepartement, true);
            }
        }

        //-------------------------------------------------------------------------------------------------------
        //-----------------------------------------DIPLOMES------------------------------------------------------
        //-------------------------------------------------------------------------------------------------------

        $diplomes = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/diplome',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $diplomes = $diplomes->toArray();
        foreach ($diplomes as $diplome) {
            //Récupérer le libellé du département du diplôme
            $dept = $departementRepository->findOneBy(['libelle' => $diplome['departement']]);

            $existingDiplome = $diplomeRepository->findOneBy(['libelle' => $diplome['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingDiplome) {
                $existingDiplome->setLibelle($diplome['libelle']);
                $existingDiplome->setSigle($diplome['sigle']);
                $existingDiplome->setDepartement($dept);
                $diplomeRepository->save($existingDiplome, true);
            } else {
                //Sinon, on le crée
                $newDiplome = new Diplome();
                $newDiplome->setLibelle($diplome['libelle']);
                $newDiplome->setSigle($diplome['sigle']);
                $newDiplome->setDepartement($dept);
                $diplomeRepository->save($newDiplome, true);
            }
        }

        //-------------------------------------------------------------------------------------------------------
        //-----------------------------------------ANNEES------------------------------------------------------
        //-------------------------------------------------------------------------------------------------------


        $this->addFlash('success', 'Les données ont bien été importées.');

        return $this->redirectToRoute('app_dashboard', [
            'departements' => $departements
        ]);
    }
}
