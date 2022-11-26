<?php

namespace App\Repository;

use App\Entity\RoomRealPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RoomRealPrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomRealPrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomRealPrice[]    findAll()
 * @method RoomRealPrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRealPriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomRealPrice::class);
    }

    /**
     * @return RoomRealPrice[] Returns an array of RoomRealPrice objects
     */
    public function findGuests($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.guests = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?RoomRealPrice
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
