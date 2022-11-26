<?php

namespace App\Repository;

use App\Entity\RoomCostCitizen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RoomCostCitizen|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomCostCitizen|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomCostCitizen[]    findAll()
 * @method RoomCostCitizen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomCostCitizenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomCostCitizen::class);
    }

    /**
     * @return RoomCostCitizen[] Returns an array of RoomCostCitizen objects
     */
    
    public function findByCitizen($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.citizen = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?RoomCostCitizen
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
