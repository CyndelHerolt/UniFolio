<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Repository;

use App\Entity\Departement;
use App\Entity\Enseignant;
use App\Entity\EnseignantDepartement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EnseignantDepartement|null find($id, $lockMode = null, $lockVersion = null)
 * @method EnseignantDepartement|null findOneBy(array $criteria, array $orderBy = null)
 * @method EnseignantDepartement[]    findAll()
 * @method EnseignantDepartement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends ServiceEntityRepository<EnseignantDepartement>
 */
class EnseignantDepartementRepository extends ServiceEntityRepository
{
    /**
     * EnseignantDepartementRepository constructor.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EnseignantDepartement::class);
    }

    public function findByEnseignant(Enseignant $user): array
    {
        return $this->createQueryBuilder('f')
            ->innerJoin(Enseignant::class, 'p', 'WITH', 'f.enseignant = p.id')
            ->innerJoin(Departement::class, 'm', 'WITH', 'f.departement = m.id')
            ->where('f.enseignant = :enseignant')
            ->setParameter('enseignant', $user)
            ->orderBy('m.libelle', Criteria::DESC)
            ->getQuery()
            ->getResult();
    }

    public function findByEnseignantDepartement(Enseignant $enseignant, Departement $departement): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.enseignant = :enseignant')
            ->andWhere('f.departement = :departement')
            ->setParameter('enseignant', $enseignant)
            ->setParameter('departement', $departement)
            ->getQuery()
            ->getResult();
    }

    public function findDroitsByEnseignantDepartement(Enseignant $enseignant, Departement $departement): array
    {
        return $this->createQueryBuilder('f')
            ->select('f.roles')
            ->where('f.enseignant = :enseignant')
            ->andWhere('f.departement = :departement')
            ->setParameter('enseignant', $enseignant)
            ->setParameter('departement', $departement)
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByEnseignantDepartement(Enseignant $enseignant, Departement $departement): ?EnseignantDepartement
    {
        return $this->createQueryBuilder('f')
            ->where('f.enseignant = :enseignant')
            ->andWhere('f.departement = :departement')
            ->setParameter('enseignant', $enseignant)
            ->setParameter('departement', $departement)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getEnseignantByDepartements(Departement $departement): array
    {
        return $this->createQueryBuilder('f')
            ->innerJoin(Enseignant::class, 'p', 'WITH', 'f.enseignant = p.id')
            ->where('f.departement = :departement')
            ->setParameter('departement', $departement)
            ->orderBy('p.nom', Criteria::ASC)
            ->addOrderBy('p.prenom', Criteria::ASC)
            ->getQuery()
            ->getResult();
    }

    public function search(string $needle, Departement $departement): array
    {
        $query = $this->createQueryBuilder('d')
            ->innerJoin(Enseignant::class, 'p', 'WITH', 'd.enseignant = p.id')
            ->where('p.nom LIKE :needle')
            ->orWhere('p.prenom LIKE :needle')
            ->orWhere('p.username LIKE :needle')
            ->orWhere('p.mailUniv LIKE :needle')
            ->andWhere('d.departement = :departement')
            ->setParameter('needle', '%' . $needle . '%')
            ->setParameter('departement', $departement->getId())
            ->orderBy('p.nom', Criteria::ASC)
            ->addOrderBy('p.prenom', Criteria::ASC)
            ->getQuery()
            ->getResult();

        $t = [];

        /** @var EnseignantDepartement $pers */
        foreach ($query as $pers) {
            $enseignant = $pers->getEnseignant();
            if (null !== $enseignant) {
                $tt = [];
                $tt['displayPr'] = $enseignant->getDisplayPr();
                $tt['slug'] = $enseignant->getSlug();
                $tt['photo'] = $enseignant->getPhotoName();
                $tt['nom'] = $enseignant->getNom();
                $tt['numeroHarpege'] = $enseignant->getNumeroHarpege();
                $tt['prenom'] = $enseignant->getPrenom();
                $tt['username'] = $enseignant->getUserIdentifier();
                $tt['mailUniv'] = $enseignant->getMailUniv();
                $tt['mailPerso'] = $enseignant->getMailPerso();
                $tt['avatarInitiales'] = $enseignant->getAvatarInitiales();

                $t[] = $tt;
            }
        }

        return $t;
    }

    public function save(EnseignantDepartement $enseignantDepartement, bool $flush = false): void
    {
        $this->_em->persist($enseignantDepartement);

        if ($flush) {
            $this->_em->flush();
        }
    }
}
