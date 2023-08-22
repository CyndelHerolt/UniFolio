<?php

namespace App\Repository;

use App\Entity\Portfolio;
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

    public function findByFilters(int $annee = null, array $groupes = [], array $etudiants = []): array
    {
        $qb = $this->createQueryBuilder('p')
            ->innerJoin('p.etudiant', 'e')
            ->innerJoin('e.groupe', 'g')
            ->innerJoin('g.type_groupe','tg')
            ->innerJoin('tg.semestre', 's')
            ->innerJoin('s.annee', 'a');
        if (!empty($annee)) {
            $qb->andWhere('a.id = :annee')
                ->setParameter('annee', $annee);
        }
        if (!empty($groupes)) {
            $qb->andWhere('g.id IN (:groupes)')
                ->setParameter('groupes', $groupes);
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
