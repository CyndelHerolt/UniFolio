<?php

namespace App\DataFixtures;

use App\Entity\Competence;
use App\Repository\ApcReferentielRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CompetenceFixture extends Fixture implements OrderedFixtureInterface
{
    private $apcReferentielRepository;

    public function __construct(ApcReferentielRepository $apcReferentielRepository)
    {
        $this->apcReferentielRepository = $apcReferentielRepository;
    }

    /**
     * @inheritDoc
     */
    public function getOrder()
    {
        return 3;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $referentiel1 = $this->apcReferentielRepository->findOneBy(['libelle' => 'Référentiel de compétences du B.U.T. MMI']);
        $referentiel2 = $this->apcReferentielRepository->findOneBy(['libelle' => 'Référentiel de compétences du B.U.T. CJ']);

        $competence1 = new Competence();
        $competence1->setId(1)
            ->setLibelle('Entreprendre dans le secteur du numérique')
            ->setNomCourt('Entreprendre')
            ->setCouleur('c5')
            ->setReferentiel($referentiel1);
        $manager->persist($competence1);

        $competence2 = new Competence();
        $competence2->setId(2)
            ->setLibelle('Développer pour le web et les médias numériques')
            ->setNomCourt('Développer')
            ->setCouleur('c4')
            ->setReferentiel($referentiel1);
        $manager->persist($competence2);

        $competence3 = new Competence();
        $competence3->setId(3)
            ->setLibelle('Comprendre les écosystèmes, les besoins des utilisateurs et les dispositifs de communication numérique')
            ->setNomCourt('Comprendre')
            ->setCouleur('c1')
            ->setReferentiel($referentiel1);
        $manager->persist($competence3);

        $competence4 = new Competence();
        $competence4->setId(4)
            ->setLibelle('Concevoir ou co-concevoir une réponse stratégique pertinente à une problématique complexe')
            ->setNomCourt('Concevoir')
            ->setCouleur('c2')
            ->setReferentiel($referentiel1);
        $manager->persist($competence4);

        $competence5 = new Competence();
        $competence5->setId(5)
            ->setLibelle('Exprimer un message avec les médias numériques pour informer et communiquer')
            ->setNomCourt('Exprimer')
            ->setCouleur('c3')
            ->setReferentiel($referentiel1);
        $manager->persist($competence5);

        $competence6 = new Competence();
        $competence6->setId(6)
            ->setLibelle('Piloter des tâches et activités d\'ordre juridique, comptable, financier, organisationnel.')
            ->setNomCourt('Piloter')
            ->setCouleur('c1')
            ->setReferentiel($referentiel2);
        $manager->persist($competence6);

        $competence7 = new Competence();
        $competence7->setId(7)
            ->setLibelle('Rédiger des actes et documents d\'ordre juridique, comptable, financier, organisationnel')
            ->setNomCourt('Rédiger')
            ->setCouleur('c4')
            ->setReferentiel($referentiel2);
        $manager->persist($competence7);

        $competence8 = new Competence();
        $competence8->setId(8)
            ->setLibelle('Sécuriser les relations et les données d\'ordre juridique, comptable, financier, organisationnel.')
            ->setNomCourt('Sécuriser')
            ->setCouleur('c3')
            ->setReferentiel($referentiel2);
        $manager->persist($competence8);

        $competence9 = new Competence();
        $competence9->setId(9)
            ->setLibelle('Conseiller sur des questions d\'ordre juridique, comptable, financier, organisationnel')
            ->setNomCourt('Conseiller')
            ->setCouleur('c2')
            ->setReferentiel($referentiel2);
        $manager->persist($competence9);

        $manager->flush();
    }

}
