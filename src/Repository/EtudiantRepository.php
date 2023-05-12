<?php

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
            $query->orWhere('e.semestre = ?'.$i)
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
