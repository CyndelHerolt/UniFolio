<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */

namespace App\Repository;

use App\Entity\Annee;
use App\Entity\Departement;
use App\Entity\Etudiant;
use App\Entity\Semestre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Etudiant>
 *
 * @method Etudiant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etudiant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etudiant[]    findAll()
 * @method Etudiant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtudiantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etudiant::class);
    }

    public function findByDepartementArray(Departement $departement): array
    {
        $t = [];
        foreach ($departement->getDiplomes() as $diplome) {
            foreach ($diplome->getAnnees() as $annee) {
                foreach ($annee->getSemestres() as $semestre) {
                    $etudiants = $this->findBySemestre($semestre);
                    /** @var Etudiant $etudiant */
                    foreach ($etudiants as $etudiant) {
                        $t[$etudiant->getUsername()] = $etudiant;
                    }
                }
            }
        }

        return $t;
    }

    public function findByDepartement(Departement $departement): array
    {
        $qb = $this->createQueryBuilder('e')
            ->innerJoin('e.groupe', 'g')
            ->innerJoin('g.type_groupe', 'tg')
            ->innerJoin('tg.semestre', 's')
            ->innerJoin('s.annee', 'a')
            ->innerJoin('a.diplome', 'd')
            ->innerJoin('d.departement', 'dep')
            ->where('dep.id = :departement')
            ->setParameter('departement', $departement->getId())
            ->orderBy('e.nom', Criteria::ASC)
            ->getQuery()
            ->getResult();

        return $qb;
    }

    public function getEtudiantGroupes(Semestre $semestre): array
    {
        $query = $this->createQueryBuilder('e')
            ->select('e.id, g.libelle')
            ->join('e.groupes', 'g')
            ->where('e.semestre = :semestre')
            ->setParameter('semestre', $semestre->getId())
            ->getQuery()
            ->getResult();

        $t = [];
        foreach ($query as $q) {
            if (!array_key_exists($q['id'], $t)) {
                $t[$q['id']] = [];
            }
            $t[$q['id']][] = $q['libelle'];
        }

        return $t;
    }

    public function findByAnnee(Annee $annee): array
    {
        $query = $this->createQueryBuilder('e');
        $i = 1;
        foreach ($annee->getSemestres() as $semestre) {
            $query->orWhere('e.semestre = ?' . $i)
                ->setParameter($i, $semestre->getId());
            ++$i;
        }

        return $query->orderBy('e.nom', Criteria::ASC)
            ->addOrderBy('e.prenom', Criteria::ASC)
            ->getQuery()
            ->getResult();
    }

    public function findBySemestreBuilder(Semestre $semestre): QueryBuilder
    {
        return $this->createQueryBuilder('e')
            ->where('e.semestre = :semestre')
            ->setParameter('semestre', $semestre)
            ->orderBy('e.nom', Criteria::ASC)
            ->addOrderBy('e.prenom', Criteria::ASC);
    }

    public function findBySemestre(Semestre $semestre): array
    {
        return $this->findBySemestreBuilder($semestre)
            ->getQuery()
            ->getResult();
    }

    public function save(Etudiant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Etudiant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByMailUniv(string $mailUniv): ?Etudiant
    {
        return $this->findOneBy(['mail_univ' => $mailUniv]);
    }

    public function findByFilters($dept, Semestre $semestre = null, array $groupes = [], array $etudiants = [], array $competences = [], int $etat = null): array
    {
        //obtenir tous les étudiants du département
        $qb = $this->createQueryBuilder('e')
            ->join('e.semestre', 's')
            ->join('s.annee', 'a')
            ->leftJoin('a.portfolio', 'p')
            ->leftJoin('p.ordrePages', 'op')
            ->leftJoin('op.page', 'pa')
            ->leftJoin('pa.ordreTraces', 'ot')
            ->leftJoin('ot.trace', 't')
            ->leftJoin('t.validations', 'v')
            ->join('e.groupe', 'g')
            ->join('a.diplome', 'd', 'WITH', 'd.departement = :departement')
            ->setParameter('departement', $dept);
        // filtre en fonction des critères
        if (!empty($semestre)) {
            $qb->andWhere('s = :semestre')
                ->setParameter('semestre', $semestre);
        }
        if (!empty($groupes)) {
            $qb->andWhere('g IN (:groupes)')
                ->setParameter('groupes', $groupes);
        }
        if (!empty($competences)) {
            $qb->andWhere('c IN (:competences)')
                ->setParameter('competences', $competences);
        }
        if (!empty($etudiants)) {
            $qb->andWhere('e IN (:etudiants)')
                ->setParameter('etudiants', $etudiants);
        }
        // Filtre des états
        if ($etat !== null) {
            if ($etat === 1) { // Portfolios évalués
                $qb->andWhere('p.etudiant = e')
                    ->andWhere('p.annee = s.annee');
                $sq = $this->createQueryBuilder('e2')
                    ->join('e2.semestre', 's2')
                    ->join('s2.annee', 'a2')
                    ->join('a2.portfolio', 'p2')
                    ->join('p2.ordrePages', 'op2')
                    ->join('op2.page', 'pa2')
                    ->join('pa2.ordreTraces', 'ot2')
                    ->join('ot2.trace', 't2')
                    ->join('t2.validations', 'v2')
                    ->where('p2.etudiant = e2')
                    ->andWhere('p2.annee = s2.annee')
                    ->andWhere('v2.etat = 0')
                    ->andWhere('e.id = e2.id');
                $qb->andWhere($qb->expr()->not($qb->expr()->exists($sq->getDQL())));
                $qb->andWhere('p.visibilite = true');
            } elseif ($etat === 2) { // non évalués
                $qb->andWhere('p.etudiant = e')
                    ->andWhere('p.annee = s.annee');
                $qb->andWhere('v.etat = 0');
                $qb->andWhere('p.visibilite = true');
            } elseif ($etat === 3) {
// Etudiants sans portfolio
                $qb->andWhere('SIZE(e.portfolios) = 0');
            }
        }

        $qb->distinct('e.id');

        return $qb->getQuery()->getResult();
    }

    public function truncate(): void
    {
        $this->createQueryBuilder('e')
            ->delete()
            ->getQuery()
            ->execute();
    }

//    /**
//     * @return Etudiant[] Returns an array of Etudiant objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Etudiant
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
