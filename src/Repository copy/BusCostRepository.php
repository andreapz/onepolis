<?php

namespace App\Repository;

use App\Entity\BusCost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
/**
 * @method BusCost|null find($id, $lockMode = null, $lockVersion = null)
 * @method BusCost|null findOneBy(array $criteria, array $orderBy = null)
 * @method BusCost[]    findAll()
 * @method BusCost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BusCostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BusCost::class);
    }

//    /**
//     * @return BusCost[] Returns an array of BusCost objects
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
    public function findOneBySomeField($value): ?BusCost
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
