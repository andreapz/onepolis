<?php

namespace App\Repository;

use App\Entity\ExtraCost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExtraCost|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExtraCost|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExtraCost[]    findAll()
 * @method ExtraCost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExtraCostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExtraCost::class);
    }

//    /**
//     * @return ExtraCost[] Returns an array of ExtraCost objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExtraCost
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
