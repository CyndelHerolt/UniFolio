<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Repository;

use App\Entity\Annee;
use App\Entity\Departement;
use App\Entity\Diplome;
use App\Entity\Semestre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Annee>
 *
 * @method Annee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annee[]    findAll()
 * @method Annee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnneeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annee::class);
    }

    public function findByDepartement(Departement $departement): array
    {
        return $this->createQueryBuilder('a')
            ->innerJoin(Diplome::class, 'd', 'WITH', 'd.id = a.diplome')
            ->where('d.departement = :departement')
            ->andWhere('a.actif = true')
            ->setParameter('departement', $departement->getId())
            ->getQuery()
            ->getResult();
    }

    public function findBySemestre(Semestre $semestre): array
    {
        return $this->createQueryBuilder('a')
            ->innerJoin(Semestre::class, 's', 'WITH', 's.annee = a.id')
            ->where('s.id = :semestre')
//            ->andWhere('a.actif = true')
            ->setParameter('semestre', $semestre->getId())
            ->getQuery()
            ->getResult();
    }

    public function save(Annee $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Annee $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function truncate(): void
    {
        $this->createQueryBuilder('d')
            ->delete()
            ->getQuery()
            ->execute();
    }

//    /**
//     * @return Annee[] Returns an array of Annee objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Annee
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
