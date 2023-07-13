<?php

namespace App\Repository;

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
