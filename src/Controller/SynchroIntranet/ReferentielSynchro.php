<?php

namespace App\Controller\SynchroIntranet;

use App\Entity\ApcApprentissageCritique;
use App\Entity\ApcNiveau;
use App\Entity\ApcParcours;
use App\Entity\ApcReferentiel;
use App\Entity\Competence;
use App\Entity\Validation;
use App\Repository\ApcApprentissageCritiqueRepository;
use App\Repository\ApcNiveauRepository;
use App\Repository\ApcParcoursRepository;
use App\Repository\ApcReferentielRepository;
use App\Repository\CompetenceRepository;
use App\Repository\DepartementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ReferentielSynchro extends AbstractController
{
    #[Route('/api/intranet/referentiel', name: 'app_synchro_intranet_referentiel')]
    public function synchroReferentiel(
        HttpClientInterface   $client,
        ApcReferentielRepository $referentielRepository,
        DepartementRepository $departementRepository,
        CompetenceRepository $competenceRepository,
        ApcNiveauRepository $niveauRepository,
        ApcApprentissageCritiqueRepository $apprentissageCritiqueRepository,
        ApcParcoursRepository $parcoursRepository,

    ): Response
    {

        $referentielRepository->truncate();
        $competenceRepository->truncate();
        $niveauRepository->truncate();
        $apprentissageCritiqueRepository->truncate();
        $parcoursRepository->truncate();


        //-------------------------------------------------------------------------------------------------------
        //-----------------------------------------REFERENTIEL---------------------------------------------------
        //-------------------------------------------------------------------------------------------------------

        $referentiels = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/referentiel',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $referentiels = $referentiels->toArray();
        foreach ($referentiels as $referentiel) {
            $dept = $departementRepository->findOneBy(['libelle' => $referentiel['departement']]);
            $existingReferentiel = $referentielRepository->findOneBy(['libelle' => $referentiel['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingReferentiel) {
                $existingReferentiel->setId($referentiel['id']);
                $existingReferentiel->setLibelle($referentiel['libelle']);
                $existingReferentiel->setDescription($referentiel['description']);
                $existingReferentiel->setAnneePublication($referentiel['annee']);
                $existingReferentiel->setDepartement($dept);
                $referentielRepository->save($existingReferentiel, true);
            } else {
                //Sinon, on le crée
                $newReferentiel = new ApcReferentiel();
                $newReferentiel->setId($referentiel['id']);
                $newReferentiel->setLibelle($referentiel['libelle']);
                $newReferentiel->setDescription($referentiel['description']);
                $newReferentiel->setAnneePublication($referentiel['annee']);
                $newReferentiel->setDepartement($dept);
                $referentielRepository->save($newReferentiel, true);
            }
        }


        //-------------------------------------------------------------------------------------------------------
        //-----------------------------------------PARCOURS------------------------------------------------------
        //-------------------------------------------------------------------------------------------------------

        $parcours = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/parcours',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $parcours = $parcours->toArray();
        foreach ($parcours as $apcParcours) {
            $referentiel = $referentielRepository->findOneBy(['libelle' => $apcParcours['referentiel']]);

            if ($referentiel) {

                $existingParcours = $parcoursRepository->findOneBy(['id' => $apcParcours['id']]);
                //Vérifier si le libelle du département existe déjà en base de données
                if ($existingParcours) {
                    $existingParcours->setId($apcParcours['id']);
                    $existingParcours->setLibelle($apcParcours['libelle']);
                    $existingParcours->setCode($apcParcours['code']);
                    $existingParcours->setActif($apcParcours['actif']);
                    $existingParcours->setApcReferentiel($referentiel);
                    $parcoursRepository->save($existingParcours, true);
                } else {
                    //Sinon, on le crée
                    $newParcours = new ApcParcours();
                    $newParcours->setId($apcParcours['id']);
                    $newParcours->setLibelle($apcParcours['libelle']);
                    $newParcours->setCode($apcParcours['code']);
                    $newParcours->setActif($apcParcours['actif']);
                    $newParcours->setApcReferentiel($referentiel);
                    $parcoursRepository->save($newParcours, true);
                }
            } else {
                $this->addFlash('error', 'Le référentiel ' . $apcParcours['referentiel'] . ' n\'existe pas en base de données. Essayez de synchroniser le référentiel depuis l\'administration.');
            }
        }

        //-------------------------------------------------------------------------------------------------------
        //-----------------------------------------COMPETENCES---------------------------------------------------
        //-------------------------------------------------------------------------------------------------------

        $competences = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/competences',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $competences = $competences->toArray();

// todo: corriger foreign key competences/niveau
        foreach ($competences as $competence) {
            $referentiel = $referentielRepository->findOneBy(['libelle' => $competence['referentiel']]);
            $existingCompetence = $competenceRepository->findOneBy(['libelle' => $competence['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingCompetence) {
                $existingCompetence->setId($competence['id']);
                $existingCompetence->setLibelle($competence['libelle']);
                $existingCompetence->setNomCourt($competence['nom_court']);
                $existingCompetence->setCouleur($competence['couleur']);
                $existingCompetence->setReferentiel($referentiel);
                $competenceRepository->save($existingCompetence, true);
//                dd($existingCompetence);
            } else {
                //Sinon, on le crée
                $newCompetence = new Competence();
                $newCompetence->setId($competence['id']);
                $newCompetence->setLibelle($competence['libelle']);
                $newCompetence->setNomCourt($competence['nom_court']);
                $newCompetence->setCouleur($competence['couleur']);
                $newCompetence->setReferentiel($referentiel);
                $competenceRepository->save($newCompetence, true);
            }
        }

        //-------------------------------------------------------------------------------------------------------
        //-----------------------------------------NIVEAU--------------------------------------------------------
        //-------------------------------------------------------------------------------------------------------

        $niveaux = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/niveau',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $niveaux = $niveaux->toArray();
        foreach ($niveaux as $niveau) {
            $competence = $competenceRepository->findOneBy(['id' => $niveau['competences']]);

//            dd($competence);

            $existingNiveau = $niveauRepository->findOneBy(['libelle' => $niveau['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingNiveau) {
                $existingNiveau->setId($niveau['id']);
                $existingNiveau->setLibelle($niveau['libelle']);
                $existingNiveau->setOrdre($niveau['ordre']);
                $existingNiveau->setCompetences($competence);
                $niveauRepository->save($existingNiveau, true);
            } else {
                //Sinon, on le crée
                $newNiveau = new ApcNiveau();
                $newNiveau->setId($niveau['id']);
                $newNiveau->setLibelle($niveau['libelle']);
                $newNiveau->setOrdre($niveau['ordre']);
                $newNiveau->setCompetences($competence);
                $niveauRepository->save($newNiveau, true);
            }
        }

        //-------------------------------------------------------------------------------------------------------
        //-----------------------------------------APPRENTISSAGES CRITIQUES--------------------------------------
        //-------------------------------------------------------------------------------------------------------

        $apprentissagesCritiques = $client->request(
            'GET',
            'http://127.0.0.1:8001/fr/api/unifolio/apprentissage_critique',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X_API_KEY' => $this->getParameter('api_key')
                ]
            ]
        );

        $apprentissagesCritiques = $apprentissagesCritiques->toArray();
        foreach ($apprentissagesCritiques as $apprentissageCritique) {
            $niveau = $niveauRepository->findOneBy(['libelle' => $apprentissageCritique['niveau']]);
            $existingApprentissageCritique = $apprentissageCritiqueRepository->findOneBy(['libelle' => $apprentissageCritique['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingApprentissageCritique) {
                $existingApprentissageCritique->setId($apprentissageCritique['id']);
                $existingApprentissageCritique->setLibelle($apprentissageCritique['libelle']);
                $existingApprentissageCritique->setCode($apprentissageCritique['code']);
                $existingApprentissageCritique->setNiveaux($niveau);
                $apprentissageCritiqueRepository->save($existingApprentissageCritique, true);
            } else {
                //Sinon, on le créé
                $newApprentissageCritique = new ApcApprentissageCritique();
                $newApprentissageCritique->setId($apprentissageCritique['id']);
                $newApprentissageCritique->setLibelle($apprentissageCritique['libelle']);
                $newApprentissageCritique->setCode($apprentissageCritique['code']);
                $newApprentissageCritique->setNiveaux($niveau);
                $apprentissageCritiqueRepository->save($newApprentissageCritique, true);
            }
        }


        $this->addFlash('success', 'Les données ont bien été importées.');

        return $this->redirectToRoute('app_dashboard', [
        ]);
    }
}
