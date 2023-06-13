<?php

namespace App\Repository;

use App\Entity\Annee;
use App\Entity\Departement;
use App\Entity\Diplome;
use App\Entity\Enseignant;
use App\Entity\EnseignantDepartement;
use App\Entity\Etudiant;
use App\Entity\Groupe;
use App\Entity\Semestre;
use App\Entity\TypeGroupe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

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

    /**
     * @throws NonUniqueResultException
     */
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
            ->where('p.enseignant = :enseignant')
            ->setParameter('enseignant', $enseignant->getId())
            ->getQuery()
            ->getResult();
    }

    public function findDepartementEnseignantDefaut(Enseignant $enseignant): array
    {
        return $this->createQueryBuilder('f')
            ->innerJoin(EnseignantDepartement::class, 'p', 'WITH', 'p.departement = f.id')
            ->where('p.enseignant = :enseignant')
            ->andWhere('p.defaut = :defaut')
            ->setParameter('enseignant', $enseignant->getId())
            ->setParameter('defaut', true)
            ->getQuery()
            ->getResult();
    }

    public function truncate(): void
    {
        $this->createQueryBuilder('d')
            ->delete()
            ->getQuery()
            ->execute();
    }

}
