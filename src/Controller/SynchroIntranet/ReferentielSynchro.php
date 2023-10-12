<?php

namespace App\Controller\SynchroIntranet;

use App\Entity\ApcApprentissageCritique;
use App\Entity\ApcNiveau;
use App\Entity\ApcParcours;
use App\Entity\ApcReferentiel;
use App\Entity\Competence;
use App\Entity\Validation;
use App\Repository\AnneeRepository;
use App\Repository\ApcApprentissageCritiqueRepository;
use App\Repository\ApcNiveauRepository;
use App\Repository\ApcParcoursRepository;
use App\Repository\ApcReferentielRepository;
use App\Repository\CompetenceRepository;
use App\Repository\DepartementRepository;
use App\Repository\DiplomeRepository;
use App\Repository\GroupeRepository;
use App\Repository\SemestreRepository;
use App\Repository\TypeGroupeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ReferentielSynchro extends AbstractController
{
    #[Route('/api/intranet/referentiel', name: 'app_synchro_intranet_referentiel')]
    public function synchroReferentiel(
        HttpClientInterface                $client,
        ApcReferentielRepository           $referentielRepository,
        DepartementRepository              $departementRepository,
        CompetenceRepository               $competenceRepository,
        ApcNiveauRepository                $niveauRepository,
        ApcApprentissageCritiqueRepository $apprentissageCritiqueRepository,
        ApcParcoursRepository              $parcoursRepository,
        ApcReferentielRepository           $apcReferentielRepository,
        ApcParcoursRepository              $apcParcoursRepository,
        GroupeRepository                   $groupeRepository,
        TypeGroupeRepository               $typeGroupeRepository,
        SemestreRepository                 $semestreRepository,
        AnneeRepository                    $anneeRepository,
        DiplomeRepository                  $diplomeRepository,
    ): Response
    {


        if ($departementRepository->findOneBy(['libelle' => 'Fixtures'])) {

            // Vide les tables
            $competenceRepository->truncate();
            $apcParcoursRepository->truncate();
            $niveauRepository->truncate();
            $apcReferentielRepository->truncate();
            $apprentissageCritiqueRepository->truncate();
            $groupeRepository->truncate();
            $typeGroupeRepository->truncate();
            $semestreRepository->truncate();
            $anneeRepository->truncate();
            $diplomeRepository->truncate();
            $departementRepository->truncate();

        }


        //-------------------------------------------------------------------------------------------------------
        //-----------------------------------------REFERENTIEL---------------------------------------------------
        //-------------------------------------------------------------------------------------------------------

        $referentiels = $client->request(
            'GET',
            $this->getParameter('api_url').'unifolio/referentiel',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'x-api-key' => $this->getParameter('api_key')
                ]
            ]
        );

        $referentiels = $referentiels->toArray();
        foreach ($referentiels as $referentiel) {
            $dept = $departementRepository->findOneBy(['id' => $referentiel['departement']]);
            $existingReferentiel = $referentielRepository->findOneBy(['id' => $referentiel['id']]);
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
            $this->getParameter('api_url').'unifolio/parcours',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'x-api-key' => $this->getParameter('api_key')
                ]
            ]
        );

        $parcours = $parcours->toArray();
        foreach ($parcours as $apcParcours) {
            $referentiel = $referentielRepository->findOneBy(['id' => $apcParcours['referentiel']]);

            if ($referentiel) {

                $existingParcours = $parcoursRepository->findOneBy(['id' => $apcParcours['id']]);
                //Vérifier si le libelle du département existe déjà en base de données
                if ($existingParcours) {
                    $existingParcours->setId($apcParcours['id']);
                    $existingParcours->setLibelle($apcParcours['libelle']);
                    $existingParcours->setCode($apcParcours['code']);
                    $existingParcours->setActif($apcParcours['actif']);
                    $existingParcours->setApcReferentiel($referentiel);
//                    foreach ($apcParcours['niveaux'] as $niveau) {
//                        $existingNiveau = $niveauRepository->findOneBy(['id' => $niveau['id']]);
//                        if ($existingNiveau) {
//                            $existingParcours->addApcNiveau($existingNiveau);
//                        }
//                    }
                    $parcoursRepository->save($existingParcours, true);
                } else {
                    //Sinon, on le crée
                    $newParcours = new ApcParcours();
                    $newParcours->setId($apcParcours['id']);
                    $newParcours->setLibelle($apcParcours['libelle']);
                    $newParcours->setCode($apcParcours['code']);
                    $newParcours->setActif($apcParcours['actif']);
                    $newParcours->setApcReferentiel($referentiel);
//                    foreach($apcParcours['niveaux'] as $niveau) {
//                        $existingNiveau = $niveauRepository->findOneBy(['id' => $niveau['id']]);
//                        if ($existingNiveau) {
//                            $newParcours->addApcNiveau($existingNiveau);
//                        }
//                    }
                    $parcoursRepository->save($newParcours, true);
                }
            } else {
                $this->addFlash('error', 'Le référentiel ' . $apcParcours['referentiel'] . ' n\'existe pas en base de données.');
            }
        }

        //-------------------------------------------------------------------------------------------------------
        //-----------------------------------------COMPETENCES---------------------------------------------------
        //-------------------------------------------------------------------------------------------------------

        //todo: passer l'année du referentiel en paramètre de la route pour récup. -> intranet pour findBy
        $competences = $client->request(
            'GET',
            $this->getParameter('api_url').'unifolio/competences',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'x-api-key' => $this->getParameter('api_key')
                ]
            ]
        );

        $competences = $competences->toArray();

        foreach ($competences as $competence) {
            $referentiel = $referentielRepository->findOneBy(['id' => $competence['referentiel']]);
            $existingCompetence = $competenceRepository->findOneBy(['id' => $competence['id']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingCompetence) {

                $existingCompetence->setId($competence['id']);
                $existingCompetence->setLibelle($competence['libelle']);
                $existingCompetence->setNomCourt($competence['nom_court']);
                $existingCompetence->setCouleur($competence['couleur']);
                $existingCompetence->setReferentiel($referentiel);
                $competenceRepository->save($existingCompetence, true);
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
            $this->getParameter('api_url').'unifolio/niveau',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'x-api-key' => $this->getParameter('api_key')
                ]
            ]
        );

        $niveaux = $niveaux->toArray();
        foreach ($niveaux as $niveau) {
            $competence = $competenceRepository->findOneBy(['id' => $niveau['competences']]);

            $existingNiveau = $niveauRepository->findOneBy(['id' => $niveau['id']]);
            //Vérifier si le libelle du département existe déjà en base de données
            if ($existingNiveau) {
                $existingNiveau->setId($niveau['id']);
                $existingNiveau->setLibelle($niveau['libelle']);
                $existingNiveau->setOrdre($niveau['ordre']);
                $existingNiveau->setCompetences($competence);
                foreach ($niveau['parcours'] as $parcours) {
                    $existingParcours = $parcoursRepository->findOneBy(['id' => $parcours['id']]);
                    if ($existingParcours && $existingNiveau) {
                        if (!$existingNiveau->getApcParcours()->contains($existingParcours)) {
                            $existingNiveau->addApcParcour($existingParcours);
                        }
                    }
                }
                $niveauRepository->save($existingNiveau, true);
            } else {
                //Sinon, on le crée
                $newNiveau = new ApcNiveau();
                $newNiveau->setId($niveau['id']);
                $newNiveau->setLibelle($niveau['libelle']);
                $newNiveau->setOrdre($niveau['ordre']);
                $newNiveau->setCompetences($competence);
                foreach ($niveau['parcours'] as $parcours) {
                    $existingParcours = $parcoursRepository->findOneBy(['id' => $parcours['id']]);
                    if ($existingParcours && $newNiveau) {
                        if (!$newNiveau->getApcParcours()->contains($existingParcours)) {
                            $newNiveau->addApcParcour($existingParcours);
                        }
                    }
                }
                $niveauRepository->save($newNiveau, true);
            }
        }



        //-------------------------------------------------------------------------------------------------------
        //-----------------------------------------APPRENTISSAGES CRITIQUES--------------------------------------
        //-------------------------------------------------------------------------------------------------------

        $apprentissagesCritiques = $client->request(
            'GET',
            $this->getParameter('api_url').'unifolio/apprentissage_critique',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'x-api-key' => $this->getParameter('api_key')
                ]
            ]
        );

        $apprentissagesCritiques = $apprentissagesCritiques->toArray();
        foreach ($apprentissagesCritiques as $apprentissageCritique) {
            $niveau = $niveauRepository->findOneBy(['id' => $apprentissageCritique['niveau']]);
            $existingApprentissageCritique = $apprentissageCritiqueRepository->findOneBy(['id' => $apprentissageCritique['id']]);
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

        return $this->redirectToRoute('app_dashboard');
    }
}
