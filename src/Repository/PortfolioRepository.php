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

    public function findByFilters($dept, Semestre $semestre = null, array $groupes = [], array $etudiants = [], array $competences = []): array
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
        $qb->distinct('p.id');

        return $qb->getQuery()->getResult();
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
