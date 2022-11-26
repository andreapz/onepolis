<?php

namespace App\Repository;

use App\Entity\BusCostCitizen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BusCostCitizen|null find($id, $lockMode = null, $lockVersion = null)
 * @method BusCostCitizen|null findOneBy(array $criteria, array $orderBy = null)
 * @method BusCostCitizen[]    findAll()
 * @method BusCostCitizen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BusCostCitizenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BusCostCitizen::class);
    }

//    /**
//     * @return BusCostCitizen[] Returns an array of BusCostCitizen objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BusCostCitizen
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
