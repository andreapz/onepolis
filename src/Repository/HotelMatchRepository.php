<?php

namespace App\Repository;

use App\Entity\HotelMatch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HotelMatchRepository|null find($id, $lockMode = null, $lockVersion = null)
 * @method HotelMatchRepository|null findOneBy(array $criteria, array $orderBy = null)
 * @method HotelMatchRepository[]    findAll()
 * @method HotelMatchRepository[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HotelMatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HotelMatch::class);
    }

    /**
     * @return HotelMatch[] Returns an array of HotelMatch objects
     */
    
    public function findByRoomReal($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.roomreal = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
     * @return HotelMatch Returns a HotelMatch object or null if not exist
     */
    
    public function findByCitizen($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.citizen = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?RoomReal
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
