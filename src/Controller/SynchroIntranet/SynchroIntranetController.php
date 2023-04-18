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

        //----------------------------------------------MMI------------------------------------------------------

        $MMI = $departementRepository->findOneBy(['libelle' => 'MMI']);
        $diplomesMMI = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/diplome/'.$MMI->getId(),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $diplomesMMI = $diplomesMMI->toArray();
        foreach ($diplomesMMI as $diplome) {
            $existingDiplome = $diplomeRepository->findOneBy(['libelle' => $diplome['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingDiplome) {
                $existingDiplome->setLibelle($diplome['libelle']);
                $existingDiplome->setSigle($diplome['sigle']);
                $existingDiplome->setDepartement($MMI);
                $diplomeRepository->save($existingDiplome, true);
                } else {
                //Sinon, on le crée
                $newDiplome = new Diplome();
                $newDiplome->setLibelle($diplome['libelle']);
                $newDiplome->setSigle($diplome['sigle']);
                $newDiplome->setDepartement($MMI);
                $diplomeRepository->save($newDiplome, true);
            }
        }

        //----------------------------------------------CJ------------------------------------------------------

        $CJ = $departementRepository->findOneBy(['libelle' => 'CJ']);
        $diplomesCJ = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/diplome/'.$CJ->getId(),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $diplomesCJ = $diplomesCJ->toArray();
        foreach ($diplomesCJ as $diplome) {
            $existingDiplome = $diplomeRepository->findOneBy(['libelle' => $diplome['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingDiplome) {
                $existingDiplome->setLibelle($diplome['libelle']);
                $existingDiplome->setSigle($diplome['sigle']);
                $existingDiplome->setDepartement($CJ);
                $diplomeRepository->save($existingDiplome, true);
            } else {
                //Sinon, on le crée
                $newDiplome = new Diplome();
                $newDiplome->setLibelle($diplome['libelle']);
                $newDiplome->setSigle($diplome['sigle']);
                $newDiplome->setDepartement($CJ);
                $diplomeRepository->save($newDiplome, true);
            }
        }

        //----------------------------------------------GMP------------------------------------------------------

        $GMP = $departementRepository->findOneBy(['libelle' => 'GMP']);
        $diplomesGMP = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/diplome/'.$GMP->getId(),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $diplomesGMP = $diplomesGMP->toArray();
        foreach ($diplomesGMP as $diplome) {
            $existingDiplome = $diplomeRepository->findOneBy(['libelle' => $diplome['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingDiplome) {
                $existingDiplome->setLibelle($diplome['libelle']);
                $existingDiplome->setSigle($diplome['sigle']);
                $existingDiplome->setDepartement($GMP);
                $diplomeRepository->save($existingDiplome, true);
            } else {
                //Sinon, on le crée
                $newDiplome = new Diplome();
                $newDiplome->setLibelle($diplome['libelle']);
                $newDiplome->setSigle($diplome['sigle']);
                $newDiplome->setDepartement($GMP);
                $diplomeRepository->save($newDiplome, true);
            }
        }

        //----------------------------------------------GEII------------------------------------------------------

        $GEII = $departementRepository->findOneBy(['libelle' => 'GEII']);
        $diplomesGEII = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/diplome/'.$GEII->getId(),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $diplomesGEII = $diplomesGEII->toArray();
        foreach ($diplomesGEII as $diplome) {
            $existingDiplome = $diplomeRepository->findOneBy(['libelle' => $diplome['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingDiplome) {
                $existingDiplome->setLibelle($diplome['libelle']);
                $existingDiplome->setSigle($diplome['sigle']);
                $existingDiplome->setDepartement($GEII);
                $diplomeRepository->save($existingDiplome, true);
            } else {
                //Sinon, on le crée
                $newDiplome = new Diplome();
                $newDiplome->setLibelle($diplome['libelle']);
                $newDiplome->setSigle($diplome['sigle']);
                $newDiplome->setDepartement($GEII);
                $diplomeRepository->save($newDiplome, true);
            }
        }


        //----------------------------------------------TC------------------------------------------------------

        $TC = $departementRepository->findOneBy(['libelle' => 'TC']);
        $diplomesTC = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/diplome/'.$TC->getId(),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $diplomesTC = $diplomesTC->toArray();
        foreach ($diplomesTC as $diplome) {
            $existingDiplome = $diplomeRepository->findOneBy(['libelle' => $diplome['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingDiplome) {
                $existingDiplome->setLibelle($diplome['libelle']);
                $existingDiplome->setSigle($diplome['sigle']);
                $existingDiplome->setDepartement($TC);
                $diplomeRepository->save($existingDiplome, true);
            } else {
                //Sinon, on le crée
                $newDiplome = new Diplome();
                $newDiplome->setLibelle($diplome['libelle']);
                $newDiplome->setSigle($diplome['sigle']);
                $newDiplome->setDepartement($TC);
                $diplomeRepository->save($newDiplome, true);
            }
        }

        //----------------------------------------------GEA------------------------------------------------------

        $GEA = $departementRepository->findOneBy(['libelle' => 'GEA']);
        $diplomesGEA = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/diplome/'.$GEA->getId(),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $diplomesGEA = $diplomesGEA->toArray();
        foreach ($diplomesGEA as $diplome) {
            $existingDiplome = $diplomeRepository->findOneBy(['libelle' => $diplome['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingDiplome) {
                $existingDiplome->setLibelle($diplome['libelle']);
                $existingDiplome->setSigle($diplome['sigle']);
                $existingDiplome->setDepartement($GEA);
                $diplomeRepository->save($existingDiplome, true);
            } else {
                //Sinon, on le crée
                $newDiplome = new Diplome();
                $newDiplome->setLibelle($diplome['libelle']);
                $newDiplome->setSigle($diplome['sigle']);
                $newDiplome->setDepartement($GEA);
                $diplomeRepository->save($newDiplome, true);
            }
        }

        //----------------------------------------------FOCO: ABF:SO------------------------------------------------------

        $FOCO = $departementRepository->findOneBy(['libelle' => 'FOCO: ABF:SO']);
        $diplomesFOCO = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/diplome/'.$FOCO->getId(),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $diplomesFOCO = $diplomesFOCO->toArray();
        foreach ($diplomesFOCO as $diplome) {
            $existingDiplome = $diplomeRepository->findOneBy(['libelle' => $diplome['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingDiplome) {
                $existingDiplome->setLibelle($diplome['libelle']);
                $existingDiplome->setSigle($diplome['sigle']);
                $existingDiplome->setDepartement($FOCO);
                $diplomeRepository->save($existingDiplome, true);
            } else {
                //Sinon, on le crée
                $newDiplome = new Diplome();
                $newDiplome->setLibelle($diplome['libelle']);
                $newDiplome->setSigle($diplome['sigle']);
                $newDiplome->setDepartement($FOCO);
                $diplomeRepository->save($newDiplome, true);
            }
        }


        //----------------------------------------------DCG------------------------------------------------------

        $DCG = $departementRepository->findOneBy(['libelle' => 'DCG']);
        $diplomesDCG = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/diplome/'.$DCG->getId(),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $diplomesDCG = $diplomesDCG->toArray();
        foreach ($diplomesDCG as $diplome) {
            $existingDiplome = $diplomeRepository->findOneBy(['libelle' => $diplome['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingDiplome) {
                $existingDiplome->setLibelle($diplome['libelle']);
                $existingDiplome->setSigle($diplome['sigle']);
                $existingDiplome->setDepartement($DCG);
                $diplomeRepository->save($existingDiplome, true);
            } else {
                //Sinon, on le crée
                $newDiplome = new Diplome();
                $newDiplome->setLibelle($diplome['libelle']);
                $newDiplome->setSigle($diplome['sigle']);
                $newDiplome->setDepartement($DCG);
                $diplomeRepository->save($newDiplome, true);
            }
        }

        //----------------------------------------------GEA FC------------------------------------------------------

        $GEAFC = $departementRepository->findOneBy(['libelle' => 'GEA FC']);
        $diplomesGEAFC = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/diplome/'.$GEAFC->getId(),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $diplomesGEAFC = $diplomesGEAFC->toArray();
        foreach ($diplomesGEAFC as $diplome) {
            $existingDiplome = $diplomeRepository->findOneBy(['libelle' => $diplome['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingDiplome) {
                $existingDiplome->setLibelle($diplome['libelle']);
                $existingDiplome->setSigle($diplome['sigle']);
                $existingDiplome->setDepartement($GEAFC);
                $diplomeRepository->save($existingDiplome, true);
            } else {
                //Sinon, on le crée
                $newDiplome = new Diplome();
                $newDiplome->setLibelle($diplome['libelle']);
                $newDiplome->setSigle($diplome['sigle']);
                $newDiplome->setDepartement($GEAFC);
                $diplomeRepository->save($newDiplome, true);
            }
        }

        //----------------------------------------------LP GRH------------------------------------------------------

        $LPGRH = $departementRepository->findOneBy(['libelle' => 'LP GRH']);
        $diplomesLPGRH = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/diplome/'.$LPGRH->getId(),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $diplomesLPGRH = $diplomesLPGRH->toArray();
        foreach ($diplomesLPGRH as $diplome) {
            $existingDiplome = $diplomeRepository->findOneBy(['libelle' => $diplome['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingDiplome) {
                $existingDiplome->setLibelle($diplome['libelle']);
                $existingDiplome->setSigle($diplome['sigle']);
                $existingDiplome->setDepartement($LPGRH);
                $diplomeRepository->save($existingDiplome, true);
            } else {
                //Sinon, on le crée
                $newDiplome = new Diplome();
                $newDiplome->setLibelle($diplome['libelle']);
                $newDiplome->setSigle($diplome['sigle']);
                $newDiplome->setDepartement($LPGRH);
                $diplomeRepository->save($newDiplome, true);
            }
        }

        //----------------------------------------------LP ABF:SO------------------------------------------------------

        $LPABFSO = $departementRepository->findOneBy(['libelle' => 'LP ABF:SO']);
        $diplomesLPABFSO = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/diplome/'.$LPABFSO->getId(),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $diplomesLPABFSO = $diplomesLPABFSO->toArray();
        foreach ($diplomesLPABFSO as $diplome) {
            $existingDiplome = $diplomeRepository->findOneBy(['libelle' => $diplome['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingDiplome) {
                $existingDiplome->setLibelle($diplome['libelle']);
                $existingDiplome->setSigle($diplome['sigle']);
                $existingDiplome->setDepartement($LPABFSO);
                $diplomeRepository->save($existingDiplome, true);
            } else {
                //Sinon, on le crée
                $newDiplome = new Diplome();
                $newDiplome->setLibelle($diplome['libelle']);
                $newDiplome->setSigle($diplome['sigle']);
                $newDiplome->setDepartement($LPABFSO);
                $diplomeRepository->save($newDiplome, true);
            }
        }

        //----------------------------------------------TC ALT------------------------------------------------------

        $TCALT = $departementRepository->findOneBy(['libelle' => 'TC ALT']);
        $diplomesTCALT = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/diplome/'.$TCALT->getId(),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $diplomesTCALT = $diplomesTCALT->toArray();
        foreach ($diplomesTCALT as $diplome) {
            $existingDiplome = $diplomeRepository->findOneBy(['libelle' => $diplome['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingDiplome) {
                $existingDiplome->setLibelle($diplome['libelle']);
                $existingDiplome->setSigle($diplome['sigle']);
                $existingDiplome->setDepartement($TCALT);
                $diplomeRepository->save($existingDiplome, true);
            } else {
                //Sinon, on le crée
                $newDiplome = new Diplome();
                $newDiplome->setLibelle($diplome['libelle']);
                $newDiplome->setSigle($diplome['sigle']);
                $newDiplome->setDepartement($TCALT);
                $diplomeRepository->save($newDiplome, true);
            }
        }

        //----------------------------------------------GMP Alt.------------------------------------------------------

        $GMPALT = $departementRepository->findOneBy(['libelle' => 'GMP Alt.']);
        $diplomesGMPALT = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/diplome/'.$GMPALT->getId(),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $diplomesGMPALT = $diplomesGMPALT->toArray();
        foreach ($diplomesGMPALT as $diplome) {
            $existingDiplome = $diplomeRepository->findOneBy(['libelle' => $diplome['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingDiplome) {
                $existingDiplome->setLibelle($diplome['libelle']);
                $existingDiplome->setSigle($diplome['sigle']);
                $existingDiplome->setDepartement($GMPALT);
                $diplomeRepository->save($existingDiplome, true);
            } else {
                //Sinon, on le crée
                $newDiplome = new Diplome();
                $newDiplome->setLibelle($diplome['libelle']);
                $newDiplome->setSigle($diplome['sigle']);
                $newDiplome->setDepartement($GMPALT);
                $diplomeRepository->save($newDiplome, true);
            }
        }

        //----------------------------------------------LP TCI------------------------------------------------------

        $LPTCI = $departementRepository->findOneBy(['libelle' => 'LP TCI']);
        $diplomesLPTCI = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/diplome/'.$LPTCI->getId(),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $diplomesLPTCI = $diplomesLPTCI->toArray();
        foreach ($diplomesLPTCI as $diplome) {
            $existingDiplome = $diplomeRepository->findOneBy(['libelle' => $diplome['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingDiplome) {
                $existingDiplome->setLibelle($diplome['libelle']);
                $existingDiplome->setSigle($diplome['sigle']);
                $existingDiplome->setDepartement($LPTCI);
                $diplomeRepository->save($existingDiplome, true);
            } else {
                //Sinon, on le crée
                $newDiplome = new Diplome();
                $newDiplome->setLibelle($diplome['libelle']);
                $newDiplome->setSigle($diplome['sigle']);
                $newDiplome->setDepartement($LPTCI);
                $diplomeRepository->save($newDiplome, true);
            }
        }

        //----------------------------------------------LP CPS------------------------------------------------------

        $LPCPS = $departementRepository->findOneBy(['libelle' => 'LP CPS']);
        $diplomesLPCPS = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/diplome/'.$LPCPS->getId(),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $diplomesLPCPS = $diplomesLPCPS->toArray();
        foreach ($diplomesLPCPS as $diplome) {
            $existingDiplome = $diplomeRepository->findOneBy(['libelle' => $diplome['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingDiplome) {
                $existingDiplome->setLibelle($diplome['libelle']);
                $existingDiplome->setSigle($diplome['sigle']);
                $existingDiplome->setDepartement($LPCPS);
                $diplomeRepository->save($existingDiplome, true);
            } else {
                //Sinon, on le crée
                $newDiplome = new Diplome();
                $newDiplome->setLibelle($diplome['libelle']);
                $newDiplome->setSigle($diplome['sigle']);
                $newDiplome->setDepartement($LPCPS);
                $diplomeRepository->save($newDiplome, true);
            }
        }

        //----------------------------------------------LP ABF CC------------------------------------------------------

        $LPABFCC = $departementRepository->findOneBy(['libelle' => 'LP ABF CC']);
        $diplomesLPABFCC = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/diplome/'.$LPABFCC->getId(),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $diplomesLPABFCC = $diplomesLPABFCC->toArray();
        foreach ($diplomesLPABFCC as $diplome) {
            $existingDiplome = $diplomeRepository->findOneBy(['libelle' => $diplome['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingDiplome) {
                $existingDiplome->setLibelle($diplome['libelle']);
                $existingDiplome->setSigle($diplome['sigle']);
                $existingDiplome->setDepartement($LPABFCC);
                $diplomeRepository->save($existingDiplome, true);
            } else {
                //Sinon, on le crée
                $newDiplome = new Diplome();
                $newDiplome->setLibelle($diplome['libelle']);
                $newDiplome->setSigle($diplome['sigle']);
                $newDiplome->setDepartement($LPABFCC);
                $diplomeRepository->save($newDiplome, true);
            }
        }

        //----------------------------------------------Passerelle------------------------------------------------------

        $Passerelle = $departementRepository->findOneBy(['libelle' => 'Passerelle']);
        $diplomesPasserelle = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/diplome/'.$Passerelle->getId(),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $diplomesPasserelle = $diplomesPasserelle->toArray();
        foreach ($diplomesPasserelle as $diplome) {
            $existingDiplome = $diplomeRepository->findOneBy(['libelle' => $diplome['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingDiplome) {
                $existingDiplome->setLibelle($diplome['libelle']);
                $existingDiplome->setSigle($diplome['sigle']);
                $existingDiplome->setDepartement($Passerelle);
                $diplomeRepository->save($existingDiplome, true);
            } else {
                //Sinon, on le crée
                $newDiplome = new Diplome();
                $newDiplome->setLibelle($diplome['libelle']);
                $newDiplome->setSigle($diplome['sigle']);
                $newDiplome->setDepartement($Passerelle);
                $diplomeRepository->save($newDiplome, true);
            }
        }

        //----------------------------------------------DSCG------------------------------------------------------

        $DSCG = $departementRepository->findOneBy(['libelle' => 'DSCG']);
        $diplomesDSCG = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/diplome/'.$DSCG->getId(),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $diplomesDSCG = $diplomesDSCG->toArray();
        foreach ($diplomesDSCG as $diplome) {
            $existingDiplome = $diplomeRepository->findOneBy(['libelle' => $diplome['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingDiplome) {
                $existingDiplome->setLibelle($diplome['libelle']);
                $existingDiplome->setSigle($diplome['sigle']);
                $existingDiplome->setDepartement($DSCG);
                $diplomeRepository->save($existingDiplome, true);
            } else {
                //Sinon, on le crée
                $newDiplome = new Diplome();
                $newDiplome->setLibelle($diplome['libelle']);
                $newDiplome->setSigle($diplome['sigle']);
                $newDiplome->setDepartement($DSCG);
                $diplomeRepository->save($newDiplome, true);
            }
        }

        //----------------------------------------------DU GOL------------------------------------------------------

        $DUGOL = $departementRepository->findOneBy(['libelle' => 'DU GOL']);
        $diplomesDUGOL = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/diplome/'.$DUGOL->getId(),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $diplomesDUGOL = $diplomesDUGOL->toArray();
        foreach ($diplomesDUGOL as $diplome) {
            $existingDiplome = $diplomeRepository->findOneBy(['libelle' => $diplome['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingDiplome) {
                $existingDiplome->setLibelle($diplome['libelle']);
                $existingDiplome->setSigle($diplome['sigle']);
                $existingDiplome->setDepartement($DUGOL);
                $diplomeRepository->save($existingDiplome, true);
            } else {
                //Sinon, on le crée
                $newDiplome = new Diplome();
                $newDiplome->setLibelle($diplome['libelle']);
                $newDiplome->setSigle($diplome['sigle']);
                $newDiplome->setDepartement($DUGOL);
                $diplomeRepository->save($newDiplome, true);
            }
        }

        //----------------------------------------------TC FC------------------------------------------------------

        $TCFC = $departementRepository->findOneBy(['libelle' => 'TC FC']);
        $diplomesTCFC = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/diplome/'.$TCFC->getId(),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $diplomesTCFC = $diplomesTCFC->toArray();
        foreach ($diplomesTCFC as $diplome) {
            $existingDiplome = $diplomeRepository->findOneBy(['libelle' => $diplome['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingDiplome) {
                $existingDiplome->setLibelle($diplome['libelle']);
                $existingDiplome->setSigle($diplome['sigle']);
                $existingDiplome->setDepartement($TCFC);
                $diplomeRepository->save($existingDiplome, true);
            } else {
                //Sinon, on le crée
                $newDiplome = new Diplome();
                $newDiplome->setLibelle($diplome['libelle']);
                $newDiplome->setSigle($diplome['sigle']);
                $newDiplome->setDepartement($TCFC);
                $diplomeRepository->save($newDiplome, true);
            }
        }

        //----------------------------------------------DAEU------------------------------------------------------

        $DAEU = $departementRepository->findOneBy(['libelle' => 'DAEU']);
        $diplomesDAEU = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/diplome/'.$DAEU->getId(),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $diplomesDAEU = $diplomesDAEU->toArray();
        foreach ($diplomesDAEU as $diplome) {
            $existingDiplome = $diplomeRepository->findOneBy(['libelle' => $diplome['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingDiplome) {
                $existingDiplome->setLibelle($diplome['libelle']);
                $existingDiplome->setSigle($diplome['sigle']);
                $existingDiplome->setDepartement($DAEU);
                $diplomeRepository->save($existingDiplome, true);
            } else {
                //Sinon, on le crée
                $newDiplome = new Diplome();
                $newDiplome->setLibelle($diplome['libelle']);
                $newDiplome->setSigle($diplome['sigle']);
                $newDiplome->setDepartement($DAEU);
                $diplomeRepository->save($newDiplome, true);
            }
        }


        $this->addFlash('success', 'Les données ont bien été importées.');

        return $this->redirectToRoute('app_dashboard', [
            'departements' => $departements
        ]);
    }
}
