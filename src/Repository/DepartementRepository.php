<?php

namespace App\Repository;

use App\Entity\Annee;
use App\Entity\Departement;
use App\Entity\Diplome;
use App\Entity\Enseignant;
use App\Entity\EnseignantDepartement;
use App\Entity\Etudiant;
use App\Entity\Semestre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @deprecated
 */

/**
 * @extends ServiceEntityRepository<Departement>
 *
 * @method Departement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Departement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Departement[]    findAll()
 * @method Departement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepartementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Departement::class);
    }

    public function save(Departement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Departement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findDepartementEtudiant(Etudiant $etudiant): ?Departement
    {
        return $this->createQueryBuilder('f')
            ->innerJoin(Diplome::class, 'd', 'WITH', 'd.departement = f.id')
            ->innerJoin(Annee::class, 'a', 'WITH', 'a.diplome = d.id')
            ->innerJoin(Semestre::class, 's', 'WITH', 's.annee = a.id')
            ->innerJoin(Etudiant::class, 'e', 'WITH', 'e.semestre = s.id')
            ->where('e.id = :etudiant')
            ->setParameter('etudiant', $etudiant->getId())
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findDepartementEnseignant(Enseignant $enseignant): array
    {
        return $this->createQueryBuilder('f')
            ->innerJoin(EnseignantDepartement::class, 'p', 'WITH', 'p.departement = f.id')
            ->where('p.personnel = :enseignant')
            ->setParameter('enseignant', $enseignant->getId())
            ->getQuery()
            ->getResult();
    }

    public function findActifs(): array
    {
        return $this->createQueryBuilder('d')
            ->where('d.actif = 1')
            ->orderBy('d.libelle')
            ->getQuery()
            ->getResult();
    }

}
