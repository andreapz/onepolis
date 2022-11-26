<?php

namespace App\Repository;

use App\Entity\TicketCost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TicketCost|null find($id, $lockMode = null, $lockVersion = null)
 * @method TicketCost|null findOneBy(array $criteria, array $orderBy = null)
 * @method TicketCost[]    findAll()
 * @method TicketCost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketCostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TicketCost::class);
    }

    /**
    * @return TicketCost[] Returns an array of TicketCost objects
    */   
    public function findByTicketType($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.ticketType = :query')
            ->setParameter('query', $value)
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
    * @return TicketCost[] Returns an array of Task objects
    */
    public function findPrices($event) {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT tcc.price, tcc.name, tcc.citizen FROM (SELECT * FROM ticket_cost_citizen tcc WHERE tcc.event = :event AND tcc.citizen IS NOT NULL) tcc';
        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['event' => $event]);

        return $execute->fetchAll();
    }
    

}
