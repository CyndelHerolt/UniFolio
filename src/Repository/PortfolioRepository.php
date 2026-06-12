<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Repository;

use App\Entity\Portfolio;
use App\Entity\Semestre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Portfolio>
 *
 * @method Portfolio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Portfolio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Portfolio[]    findAll()
 * @method Portfolio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PortfolioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Portfolio::class);
    }

    public function save(Portfolio $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Portfolio $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    private function createFiltersQb($dept, ?Semestre $semestre = null, array $groupes = [], array $etudiants = [], array $competences = [], ?int $etat = null)
    {
        $qb = $this->createQueryBuilder('p')
            ->innerJoin('p.ordrePages', 'op')
            ->innerJoin('op.page', 'pa')
            ->innerJoin('pa.ordreTraces', 'ot')
            ->innerJoin('ot.trace', 't')
            ->innerJoin('t.validations', 'v')
            ->innerJoin('v.apcNiveau', 'c')
            ->innerJoin('p.etudiant', 'e')
            ->innerJoin('e.groupe', 'g')
            ->innerJoin('e.semestre', 's')
            ->innerJoin('s.annee', 'a')
            ->innerJoin('a.diplome', 'd')
            ->innerJoin('d.departement', 'dep')
            ->where('dep.id = :departement')
            ->andWhere('p.visibilite = true')
            ->setParameter('departement', $dept);
        if ($etat !== null) {
            switch($etat) {
                case 0: // Tous les portfolios
                    break;
                case 1: // Portfolios évalués
                    $portfolios = $qb->getQuery()->getResult();  //Obtenir tous les portfolios
                    $evaluatedPortfolios = [];

                    foreach($portfolios as $portfolio){
                        $totalValidations = 0;
                        $totalEval = 0;

                        foreach($portfolio->getOrdrePages() as $op){
                            foreach($op->getPage()->getOrdreTraces() as $ot){
                                foreach($ot->getTrace()->getValidations() as $validation){
                                    $totalValidations++;
                                    if($validation->isEtat() != 0){
                                        $totalEval++;
                                    }
                                }
                            }
                        }

                        //Si toutes les validations sont évaluées, ajouter le portfolio à la liste
                        if($totalValidations == $totalEval){
                            $evaluatedPortfolios[] = $portfolio;
                        }
                    }
                    return $evaluatedPortfolios;
                case 2: // Portfolios non évalués
                    $qb->andWhere('v.etat = 0');
                    break;
            }
        }
        if (!empty($semestre)) {
            $qb->andWhere('s.id = :semestre')
                ->setParameter('semestre', $semestre->getId());
        }
        if (!empty($groupes)) {
            $qb->andWhere('g.id IN (:groupes)')
                ->setParameter('groupes', $groupes);
        }
        if (!empty($competences)) {
            $qb->andWhere('c.id IN (:competences)')
                ->setParameter('competences', $competences);
        }
        if (!empty($etudiants)) {
            $qb->andWhere('e.id IN (:etudiants)')
                ->setParameter('etudiants', $etudiants);
        }

        return $qb;
    }

    public function countByFilters($dept, ?Semestre $semestre = null, array $groupes = [], array $etudiants = [], array $competences = [], ?int $etat = null): int
    {
        $qb = $this->createFiltersQb($dept, $semestre, $groupes, $etudiants, $competences, $etat);
        if (is_array($qb)) {
            return count($qb);
        }

        return (int) $qb
            ->select('COUNT(DISTINCT p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findIdsByFilters($dept, ?Semestre $semestre = null, array $groupes = [], array $etudiants = [], array $competences = [], ?int $etat = null, int $limit = 20, int $offset = 0): array
    {
        $qb = $this->createFiltersQb($dept, $semestre, $groupes, $etudiants, $competences, $etat);
        if (is_array($qb)) {
            $slice = array_slice($qb, $offset, $limit);
            return array_map(static fn (Portfolio $portfolio) => (int) $portfolio->getId(), $slice);
        }

        $rows = $qb
            ->select('DISTINCT p.id AS id')
            ->orderBy('p.date_modification', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getArrayResult();

        return array_map(static fn (array $row) => (int) $row['id'], $rows);
    }

    public function findByFilters($dept, ?Semestre $semestre = null, array $groupes = [], array $etudiants = [], array $competences = [], ?int $etat = null): array
    {
        $qb = $this->createFiltersQb($dept, $semestre, $groupes, $etudiants, $competences, $etat);
        if (is_array($qb)) {
            return $qb;
        }

        $qb->distinct('p.id');

        return $qb->getQuery()->getResult();
    }

    public function findOneForEvaluationCard(int $id): ?Portfolio
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.etudiant', 'e')->addSelect('e')
            ->leftJoin('e.groupe', 'g')->addSelect('g')
            ->leftJoin('g.type_groupe', 'tg')->addSelect('tg')
            ->leftJoin('e.semestre', 's')->addSelect('s')
            ->leftJoin('p.ordrePages', 'op')->addSelect('op')
            ->leftJoin('op.page', 'pa')->addSelect('pa')
            ->leftJoin('pa.ordreTraces', 'ot')->addSelect('ot')
            ->leftJoin('ot.trace', 't')->addSelect('t')
            ->leftJoin('t.validations', 'v')->addSelect('v')
            ->leftJoin('v.apcNiveau', 'n')->addSelect('n')
            ->leftJoin('n.competences', 'c')->addSelect('c')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }


//    /**
//     * @return Portfolio[] Returns an array of Portfolio objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Portfolio
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
