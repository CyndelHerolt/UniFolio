<?php
/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Repository;

use App\Entity\Semestre;
use App\Entity\Trace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trace>
 *
 * @method Trace|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trace|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trace[]    findAll()
 * @method Trace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TraceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trace::class);
    }

    public function save(Trace $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Trace $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // Requete pour récupérer les traces en fonction des compétences récupérées dans le controller
    public function findByCompetence(array $competences): array
    {
        $qb = $this->createQueryBuilder('t')
            ->join('t.validations', 'v')
            ->join('v.apcNiveau', 'c')
            ->where('c.id IN (:competences)')
            ->setParameter('competences', $competences)
            ->getQuery()
            ->getResult();

        return $qb;
    }

    public function findByFilters($dept, Semestre $semestre = null, array $competences = [], array $groupes = [], array $etudiants = []): array
    {
        $qb = $this->createQueryBuilder('t')
            ->innerJoin('t.validations', 'v')
            ->innerJoin('v.apcNiveau', 'c')
            ->join('t.bibliotheque', 'b')
            ->join('b.etudiant', 'e')
            ->join('e.groupe', 'g')
            ->join('e.semestre', 's')
            ->join('s.annee', 'a')
            ->join('a.diplome', 'd')
            ->join('d.departement', 'dep')
            ->where('dep.id = :departement')
            ->setParameter('departement', $dept)
            ;
        if (!empty($semestre)) {
            $qb->andWhere('s.id = :semestre')
                ->setParameter('semestre', $semestre->getId());
        }
        if (!empty($competences)) {
            $qb->andWhere('c.id IN (:competences)')
                ->setParameter('competences', $competences);
        }
        if (!empty($groupes)) {
            $qb->andWhere('g.id IN (:groupes)')
                ->setParameter('groupes', $groupes);
        }
        if (!empty($etudiants)) {
            $qb->andWhere('e.id IN (:etudiants)')
                ->setParameter('etudiants', $etudiants);
        }
        $qb->distinct('t.id');

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Trace[] Returns an array of Trace objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Trace
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
