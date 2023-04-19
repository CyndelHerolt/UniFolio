<?php

namespace App\Controller\SynchroIntranet;

use App\Entity\Annee;
use App\Entity\Departement;
use App\Entity\Diplome;
use App\Entity\Groupe;
use App\Entity\Semestre;
use App\Entity\TypeGroupe;
use App\Repository\AnneeRepository;
use App\Repository\DepartementRepository;
use App\Repository\DiplomeRepository;
use App\Repository\GroupeRepository;
use App\Repository\SemestreRepository;
use App\Repository\TypeGroupeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SynchroIntranetController extends AbstractController
{
    #[Route('/api/intranet/structure', name: 'app_synchro_intranet_structure')]
    public function index(
        HttpClientInterface   $client,
        DepartementRepository $departementRepository,
        DiplomeRepository     $diplomeRepository,
        AnneeRepository       $anneeRepository,
        SemestreRepository    $semestreRepository,
        TypeGroupeRepository  $typeGroupeRepository,
        GroupeRepository      $groupeRepository,


    ): Response
    {
        //https://symfony.com/doc/current/http_client.html

        //-------------------------------------------------------------------------------------------------------
        //-----------------------------------------DEPARTEMENTS--------------------------------------------------
        //-------------------------------------------------------------------------------------------------------

        $departements = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/departement',
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

        $annees = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/annee',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $annees = $annees->toArray();
        foreach ($annees as $annee) {
            $diplome = $diplomeRepository->findOneBy(['libelle' => $annee['diplome']]);

            $existingAnnee = $anneeRepository->findOneBy(['libelle' => $annee['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingAnnee) {
                $existingAnnee->setLibelle($annee['libelle']);
                $existingAnnee->setOrdre($annee['ordre']);
                $existingAnnee->setLibelleLong($annee['libelle_long']);
                $existingAnnee->setOptAlternance($annee['opt_alternance']);
                $existingAnnee->setActif($annee['actif']);
                $existingAnnee->setDiplome($diplome);
                $anneeRepository->save($existingAnnee, true);
            } else {
                //Sinon, on le crée
                $newAnnee = new Annee();
                $newAnnee->setLibelle($annee['libelle']);
                $newAnnee->setOrdre($annee['ordre']);
                $newAnnee->setLibelleLong($annee['libelle_long']);
                $newAnnee->setOptAlternance($annee['opt_alternance']);
                $newAnnee->setActif($annee['actif']);
                $newAnnee->setDiplome($diplome);
                $anneeRepository->save($newAnnee, true);
            }
        }

        //-------------------------------------------------------------------------------------------------------
        //-----------------------------------------SEMESTRES-----------------------------------------------------
        //-------------------------------------------------------------------------------------------------------

        $semestres = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/semestre',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ],
        );

        $semestres = $semestres->toArray();
        foreach ($semestres as $semestre) {
            $annee = $anneeRepository->findOneBy(['libelle' => $semestre['annee']]);

            $existingSemestre = $semestreRepository->findOneBy(['libelle' => $semestre['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingSemestre) {
                $existingSemestre->setLibelle($semestre['libelle']);
                $existingSemestre->setOrdreAnnee($semestre['ordreAnnee']);
                $existingSemestre->setOrdreLmd($semestre['ordreLmd']);
                $existingSemestre->setCodeElement($semestre['code']);
                $existingSemestre->setActif($semestre['actif']);
                $existingSemestre->setNbGroupesCm($semestre['nb_groupes_cm']);
                $existingSemestre->setNbGroupesTd($semestre['nb_groupes_td']);
                $existingSemestre->setNbGroupesTp($semestre['nb_groupes_tp']);
                $existingSemestre->setAnnee($annee);
                $semestreRepository->save($existingSemestre, true);
            } else {
                //Sinon, on le crée
                $newSemestre = new Semestre();
                $newSemestre->setLibelle($semestre['libelle']);
                $newSemestre->setOrdreAnnee($semestre['ordreAnnee']);
                $newSemestre->setOrdreLmd($semestre['ordreLmd']);
                $newSemestre->setCodeElement($semestre['code']);
                $newSemestre->setActif($semestre['actif']);
                $newSemestre->setNbGroupesCm($semestre['nb_groupes_cm']);
                $newSemestre->setNbGroupesTd($semestre['nb_groupes_td']);
                $newSemestre->setNbGroupesTp($semestre['nb_groupes_tp']);
                $newSemestre->setAnnee($annee);
                $semestreRepository->save($newSemestre, true);
            }
        }

        //-------------------------------------------------------------------------------------------------------
        //-----------------------------------------TYPES GROUPES-------------------------------------------------
        //-------------------------------------------------------------------------------------------------------

        $typesGroupes = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/type_groupe',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $typesGroupes = $typesGroupes->toArray();
        foreach ($typesGroupes as $typeGroupe) {
            $existingTypeGroupe = $typeGroupeRepository->findOneBy(['libelle' => $typeGroupe['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingTypeGroupe) {
                $existingTypeGroupe->setLibelle($typeGroupe['libelle']);
                $existingTypeGroupe->setOrdreSemestre($typeGroupe['ordre']);
                foreach ($typeGroupe['semestres'] as $semestre) {
                    $semestre = $semestreRepository->findOneBy(['libelle' => $semestre['libelle']]);
                    $existingTypeGroupe->addSemestre($semestre);
                }
                $typeGroupeRepository->save($existingTypeGroupe, true);
            } else {
                //Sinon, on le crée
                $newTypeGroupe = new TypeGroupe();
                $newTypeGroupe->setLibelle($typeGroupe['libelle']);
                $newTypeGroupe->setOrdreSemestre($typeGroupe['ordre']);
                foreach ($typeGroupe['semestres'] as $semestre) {
                    $semestre = $semestreRepository->findOneBy(['libelle' => $semestre['libelle']]);
                    $newTypeGroupe->addSemestre($semestre);
                }
                $typeGroupeRepository->save($newTypeGroupe, true);
            }
        }

        //-------------------------------------------------------------------------------------------------------
        //-----------------------------------------------GROUPES-------------------------------------------------
        //-------------------------------------------------------------------------------------------------------

        $groupes = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/groupe',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $groupes = $groupes->toArray();
        foreach ($groupes as $groupe) {

            $existingGroupe = $groupeRepository->findOneBy(['libelle' => $groupe['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingGroupe) {
                $existingGroupe->setLibelle($groupe['libelle']);
                $existingGroupe->setOrdre($groupe['ordre']);
                $existingGroupe->setCodeApogee($groupe['code']);
                foreach ($groupe['type'] as $typeGroupe) {
                    $typeGroupe = $typeGroupeRepository->findOneBy(['libelle' => $typeGroupe['libelle']]);
                    $existingGroupe->setTypeGroupe($typeGroupe);
                }
                $groupeRepository->save($existingGroupe, true);
            } else {
                //Sinon, on le crée
                $newGroupe = new Groupe();
                $newGroupe->setLibelle($groupe['libelle']);
                $newGroupe->setOrdre($groupe['ordre']);
                $newGroupe->setCodeApogee($groupe['code']);
                foreach ($groupe['type'] as $typeGroupe) {
                    $typeGroupe = $typeGroupeRepository->findOneBy(['libelle' => $typeGroupe['libelle']]);
                    $newGroupe->setTypeGroupe($typeGroupe);
                }
                $groupeRepository->save($newGroupe, true);
            }
        }


        $this->addFlash('success', 'Les données ont bien été importées.');

        return $this->redirectToRoute('app_dashboard', [
            'departements' => $departements
        ]);
    }

    #[Route('/api/intranet/referentiel', name: 'app_synchro_intranet_referentiel')]
    public function synchroReferentiel(
        HttpClientInterface   $client,
    ): Response
    {


        $this->addFlash('success', 'Les données ont bien été importées.');

        return $this->redirectToRoute('app_dashboard', [
        ]);
    }
}
