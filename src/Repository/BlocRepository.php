<?php
/*
 * Copyright (c) 2024. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Repository;

use App\Entity\Bloc;
use App\Entity\Page;
use App\Entity\PagePerso;
use App\Entity\PortfolioPerso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bloc>
 *
 * @method Bloc|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bloc|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bloc[]    findAll()
 * @method Bloc[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlocRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bloc::class);
    }

    public function save(Bloc $bloc): Bloc
    {
        $this->_em->persist($bloc);
        $this->_em->flush();

        return $bloc;
    }

    public function remove(Bloc $bloc): void
    {
        $this->_em->remove($bloc);
        $this->_em->flush();
    }

    public function findByPage(PagePerso $page): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere(':page MEMBER OF b.pages')
            ->setParameter('page', $page)
            ->orderBy('b.ordre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByPortfolio(PortfolioPerso $portfolio)
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.pages', 'p')
            ->andWhere('p.portfolio = :portfolio')
            ->setParameter('portfolio', $portfolio)
            ->orderBy('b.ordre', 'ASC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Bloc[] Returns an array of Bloc objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Bloc
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
