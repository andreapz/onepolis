<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Test\Entity;

use App\Entity\Room;
use App\Entity\RoomBase;
use App\Entity\Citizen;
use App\Entity\RoomCost;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;


class RoomCostTest extends TestCase {
    private $eventId = 1;
    private $roomBaseId1 = 1;
    private $roomId1 = 1;
    private $roomId2 = 2;
    
    private $roomBaseId3 = 3;
    private $roomId3 = 3;
    
    private $userId = 1;
    private $citizenId = 2;
        
    public function createTicket($id, $price, $name,
            $minAge, $maxAge, $initialDate, $endDate, $roomManager) {
        $ticketManager = $this->createMock(RoomCost::class);
        $ticketManager->expects($this->any())
            ->method('getId')->willReturn($id);
        $ticketManager->expects($this->any())
            ->method('getPrice')->willReturn($price);
        $ticketManager->expects($this->any())
            ->method('getName')->willReturn($name);
        $ticketManager->expects($this->any())
            ->method('getMinAge')->willReturn($minAge);
        $ticketManager->expects($this->any())
            ->method('getMaxAge')->willReturn($maxAge);
        $ticketManager->expects($this->any())
            ->method('getInitialDate')->willReturn($initialDate);
        $ticketManager->expects($this->any())
            ->method('getEndDate')->willReturn($endDate);
        $ticketManager->expects($this->any())
            ->method('getRoom')->willReturn($roomManager);
        return $ticketManager;
    }
    
    public function createTicket1($roomManager) {
        return $this->createTicket(1, "11,00", "TK1", 10, 100,
                new \DateTime("2020-01-01"),
                new \DateTime("2020-03-01"),
                $roomManager);
    }
    
    public function createTicket2($roomManager) {
        return $this->createTicket(2, "22,00", "TK2", 10, 100,
                new \DateTime("2020-03-02"),
                new \DateTime("2020-05-01"),
                $roomManager);
    }
    
    public function createTicket3($roomManager) {
        return $this->createTicket(3, "33,00", "TC2", 0, 100,
                new \DateTime("2020-01-02"),
                new \DateTime("2020-05-01"),
                $roomManager);
    }
    
    public function createRoom($roomBaseId, $roomId, $eventId) {
        $roomBaseManager = $this->createMock(RoomBase::class);
        $roomBaseManager->expects($this->any())
            ->method('getId')->willReturn($roomBaseId);
        
        $roomManager = $this->createMock(Room::class);
        $roomManager->expects($this->any())
            ->method('getParent')->willReturn($roomBaseManager);
                
        $roomManager->setParent($roomBaseManager);
        
        $roomManager->expects($this->any())
            ->method('getEid')->willReturn($eventId);
        
        $roomManager->expects($this->any())
            ->method('getId')->willReturn($roomId); 
        
        return $roomManager;
    }
    
    public function createRoom1() {
        $roomManager =  $this->createRoom($this->roomBaseId1, 
                $this->roomId1, $this->eventId);
        
        $tickets = new ArrayCollection();
        $tickets[] = $this->createTicket1($roomManager);
        $tickets[] = $this->createTicket2($roomManager);
        
        $roomManager->expects($this->any())
            ->method('getTickets')->willReturn($tickets); 
        
        return $roomManager;
    }

    public function createRoom3() {
        $roomManager = $this->createRoom($this->roomBaseId3, 
                $this->roomId3, $this->eventId);
        
        $tickets = new ArrayCollection();
        $tickets[] = $this->createTicket3($roomManager);
        
        $roomManager->expects($this->any())
            ->method('getTickets')->willReturn($tickets); 
        
        return $roomManager;
    }
    
    public function testTicketIsValid() {
        $citizen = new Citizen();
        $citizen->setBirthDate(new \DateTime("1978-08-21"));
        
        $roomManager = $this->createRoom1();
        
        $ticket1Manager = $this->createTicket1($roomManager);
        $ticket2Manager = $this->createTicket2($roomManager);
        
        $now1 = new \DateTime("2020-02-01");
        
        $this->assertEquals(true, RoomCost::isTicketValid($ticket1Manager, 
                $this->roomId1, $now1, $citizen->getBirthDate()));
        
        $this->assertEquals(false, RoomCost::isTicketValid($ticket2Manager, 
                $this->roomId1, $now1, $citizen->getBirthDate()));
        
        $now2 = new \DateTime("2020-03-02");
        
        $this->assertEquals(false, RoomCost::isTicketValid($ticket1Manager, 
                $this->roomId1, $now2, $citizen->getBirthDate()));
        
        $this->assertEquals(true, RoomCost::isTicketValid($ticket2Manager, 
                $this->roomId1, $now2, $citizen->getBirthDate()));
    }
    
    public function testRegisterRoom() {
        $citizen = new Citizen();
        $citizen->setBirthDate(new \DateTime("1978-08-21"));
        
        $roomManager1 = $this->createRoom1();
        $roomManager2 = $this->createRoom3();
        $roomManagers = [$roomManager1, $roomManager2];
        
        $now1 = new \DateTime("2020-02-01");
        
        $citizen->registerRoom($this->roomId1, $roomManagers, $this->userId, 
                $this->citizenId, $now1);
        $tickets1 = $citizen->getTicketsroom();
        
        $this->assertEquals(1, count($tickets1));
        
        $tickets1_0 = $tickets1[0];
        $this->assertEquals("TK1", $tickets1_0->getName());
        
    }
}
