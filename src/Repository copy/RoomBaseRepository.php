<?php

namespace App\Repository;

use App\Entity\RoomBase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RoomBase|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomBase|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomBase[]    findAll()
 * @method RoomBase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomBaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomBase::class);
    }

//    /**
//     * @return RoomBase[] Returns an array of RoomBase objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RoomBase
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
