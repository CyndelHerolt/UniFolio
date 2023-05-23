<?php

namespace App\Repository;

use App\Entity\Annee;
use App\Entity\Departement;
use App\Entity\Diplome;
use App\Entity\Groupe;
use App\Entity\Semestre;
use App\Entity\TypeGroupe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Groupe>
 *
 * @method Groupe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Groupe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Groupe[]    findAll()
 * @method Groupe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Groupe::class);
    }

    public function findByDepartementBuilder(Departement $departement): QueryBuilder
    {
        return $this->createQueryBuilder('g')
        // seuls ceux qui ont une correspondance entre les colonnes 'type_groupe' de la table "Groupe" et 'id' de la table "TypeGroupe" seront inclus dans les résultats
            ->innerJoin(TypeGroupe::class, 't', 'WITH', 'g.type_groupe = t.id')
            ->innerJoin('t.semestre', 's')
            ->innerJoin(Annee::class, 'a', 'WITH', 'a.id = s.annee')
            ->innerJoin(Diplome::class, 'd', 'WITH', 'd.id = a.diplome')
            ->where('d.departement = :departement')
            ->setParameter('departement', $departement->getId());
    }

    public function findByDepartement(Departement $departement): array
    {
        return $this->findByDepartementBuilder($departement)
            ->getQuery()
            ->getResult();
    }

    public function findBySemestreBuilder(Semestre $semestre): QueryBuilder
    {
        return $this->createQueryBuilder('g')
            ->innerJoin(TypeGroupe::class, 't', 'WITH', 'g.type_groupe = t.id')
            ->where('t.semestre = :semestre')
            ->setParameter('semestre', $semestre->getId())
            ->orderBy('t.libelle', Criteria::ASC)
            ->addOrderBy('g.libelle', Criteria::ASC);
    }

    public function findBySemestre(Semestre $semestre): array
    {
        return $this->findBySemestreBuilder($semestre)
            ->getQuery()
            ->getResult();
    }

    //todo: a corriger
    public function findByDepartementSemestreActifBuilder(Departement $departement): QueryBuilder
    {
        return $this->createQueryBuilder('g')
            ->innerJoin(TypeGroupe::class, 't', 'WITH', 'g.type_groupe = t.id')
            ->innerJoin('t.semestre', 's')
            ->innerJoin(Annee::class, 'a', 'WITH', 'a.id = s.annee')
            ->innerJoin(Diplome::class, 'd', 'WITH', 'd.id = a.diplome')
            ->where('d.departement = :departement')
            ->andWhere('s.actif = 1')
            ->andWhere('t.libelle = :tlibelle')
            ->setParameter('tlibelle', 'TP')
            ->setParameter('departement', $departement->getId());
    }

    public function findByDepartementSemestreActif(Departement $departement): array
    {
        return $this->findByDepartementSemestreActifBuilder($departement)
            ->getQuery()
            ->getResult();
    }
    public function findByTypeGroupe(?TypeGroupe $typegroupe): array
    {
        return $this->createQueryBuilder('g')
            ->where('g.type_groupe = :type_groupe')
            ->orderBy('g.ordre', Criteria::ASC)
            ->setParameter('type_groupe', $typegroupe)
            ->getQuery()
            ->getResult();
    }

    public function findGroupesEtudiant($etudiant): array
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.etudiants', 'e')
            ->where('e.id = :etudiant')
            ->setParameter('etudiant', $etudiant)
            ->getQuery()
            ->getResult();
    }

    public function save(Groupe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Groupe $entity, bool $flush = false): void
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
//     * @return Groupe[] Returns an array of Groupe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Groupe
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
