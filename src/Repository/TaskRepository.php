<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\User;
use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }
    
    public function findOneId($value): ?Task
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
    public function findOneByCode($code)
    {
        if (empty($code)) {
            return null;
        }
        
        return $this->createQueryBuilder('t')
            ->andWhere('t.code like :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
    public function findAllByEvent($event)
    {
        if($event && $event < 1) {
            return null;
        }
        
        return $this->createQueryBuilder('t')
            ->andWhere('t.event = :event')
            ->setParameter('event', $event)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllByEventAndUser($event, $user)
    {
        if($event && $event < 1) {
            return null;
        }
        
        return $this->createQueryBuilder('t')
            ->andWhere('t.event = :event')
            ->andWhere('t.uid = :uid')
            ->setParameter('event', $event)
            ->setParameter('uid', $user)
            ->getQuery()
            ->getResult()
        ;
    }
    
    
    /**
    * @return Task[] Returns an array of Task objects
    */
    public function findByUser($event, $user, $room) {

        $search = 'AND c.uid = ' . $user;
        
        return $this->findByAdmin($event, $search, $room);
    }

    /**
    * @return Task[] Returns an array of Task objects
    */
    public function findByAdmin($event, $search, $room) {

        $preroom = '';
        $postroom = '';
        if ($room > 0) {
            $preroom = '(SELECT * FROM room_cost_citizen rcc WHERE rcc.rid = ' . $room .' ) rcc
            LEFT JOIN';
            $postroom = 'ON rcc.citizen = c.id';
        }

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT t.id tid, event, t.uid, ordered, ordered_date, amount, t.code, cp.id cpid, value, description, type, payment_date 
                FROM (SELECT * FROM task t WHERE t.id IN 


                    (SELECT c.task FROM ' . $preroom . ' 
                        (SELECT c.* FROM citizen c WHERE c.eid = :event AND c.deleted = 0 ' . $search .' ) c ' . $postroom . ' 
                    GROUP BY c.task)  ) t 
                LEFT JOIN (SELECT * FROM citizen_payment cp WHERE cp.deleted = 0) cp ON t.code = cp.code';
        $stmt = $conn->prepare($sql);
        $execute = $stmt->execute(['event' => $event]);

        return $execute->fetchAll();
    }
    
//    /**
//     * @return Task[] Returns an array of Task objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
