<?php

namespace App\Repository;

use App\Entity\CitizenPayment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CitizenPayment|null find($id, $lockMode = null, $lockVersion = null)
 * @method CitizenPayment|null findOneBy(array $criteria, array $orderBy = null)
 * @method CitizenPayment[]    findAll()
 * @method CitizenPayment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CitizenPaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CitizenPayment::class);
    }
    
     /**
    * @return CitizenPayment[] Returns an array of CitizenPayment objects
    */   
    public function findAllByTask($tid)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.tid = :tid')
            ->andWhere('c.deleted = 0')
            ->setParameter('tid', $tid)
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return CitizenPayment[] Returns an array of CitizenPayment objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CitizenPayment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
