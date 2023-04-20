<?php

namespace App\Controller\SynchroIntranet;

use App\Entity\ApcApprentissageCritique;
use App\Entity\ApcNiveau;
use App\Entity\ApcReferentiel;
use App\Entity\Competence;
use App\Repository\ApcApprentissageCritiqueRepository;
use App\Repository\ApcNiveauRepository;
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
    ): Response
    {
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
                $existingReferentiel->setLibelle($referentiel['libelle']);
                $existingReferentiel->setDescription($referentiel['description']);
                $existingReferentiel->setAnneePublication($referentiel['annee']);
                $existingReferentiel->setDepartement($dept);
                $referentielRepository->save($existingReferentiel, true);
            } else {
                //Sinon, on le crée
                $newReferentiel = new ApcReferentiel();
                $newReferentiel->setLibelle($referentiel['libelle']);
                $newReferentiel->setDescription($referentiel['description']);
                $newReferentiel->setAnneePublication($referentiel['annee']);
                $newReferentiel->setDepartement($dept);
                $referentielRepository->save($newReferentiel, true);
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
        foreach ($competences as $competence) {
            $referentiel = $referentielRepository->findOneBy(['libelle' => $competence['referentiel']]);
            $existingCompetence = $competenceRepository->findOneBy(['libelle' => $competence['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingCompetence) {
                $existingCompetence->setLibelle($competence['libelle']);
                $existingCompetence->setNomCourt($competence['nom_court']);
                $existingCompetence->setCouleur($competence['couleur']);
                $existingCompetence->setReferentiel($referentiel);
                $competenceRepository->save($existingCompetence, true);
            } else {
                //Sinon, on le crée
                $newCompetence = new Competence();
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
            $competence = $competenceRepository->findOneBy(['libelle' => $niveau['competences']]);
            $existingNiveau = $niveauRepository->findOneBy(['libelle' => $niveau['libelle']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingNiveau) {
                $existingNiveau->setLibelle($niveau['libelle']);
                $existingNiveau->setOrdre($niveau['ordre']);
                $existingNiveau->setCompetences($competence);
                $niveauRepository->save($existingNiveau, true);
            } else {
                //Sinon, on le crée
                $newNiveau = new ApcNiveau();
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
                $existingApprentissageCritique->setLibelle($apprentissageCritique['libelle']);
                $existingApprentissageCritique->setCode($apprentissageCritique['code']);
                $existingApprentissageCritique->setNiveaux($niveau);
                $apprentissageCritiqueRepository->save($existingApprentissageCritique, true);
            } else {
                //Sinon, on le crée
                $newApprentissageCritique = new ApcApprentissageCritique();
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
