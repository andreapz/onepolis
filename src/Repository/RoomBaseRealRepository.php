<?php

namespace App\Repository;

use App\Entity\RoomBaseReal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RoomBaseReal|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomBaseReal|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomBaseReal[]    findAll()
 * @method RoomBaseReal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomBaseRealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomBaseReal::class);
    }

//    /**
//     * @return RoomBaseReal[] Returns an array of RoomBaseReal objects
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
    public function findOneBySomeField($value): ?RoomBaseReal
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
