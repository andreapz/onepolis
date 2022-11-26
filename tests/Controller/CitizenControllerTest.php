<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Test\Controller;

use App\Entity\Room;
use App\Entity\RoomBase;
use App\Entity\Citizen;
use App\Entity\RoomCost;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

use App\Test\Entity\RestaurantCostTest;

class CitizenControllerTest extends TestCase {
    
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
        return $this->createTicket(3, "5,00", "TK1 bimbi", 0, 9,
                new \DateTime("2020-01-01"),
                new \DateTime("2020-03-01"),
                $roomManager);
    }
    
    public function createRoom($eventId, $roomBaseId, $roomId) {
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
        
        $ticket1Manager = $this->createTicket1($roomManager);
        $ticket2Manager = $this->createTicket2($roomManager);
        $ticket3Manager = $this->createTicket3($roomManager);
                
        $tickets = new ArrayCollection();
        $tickets[] = $ticket1Manager;
        $tickets[] = $ticket2Manager;
        $tickets[] = $ticket3Manager;
        
        $roomManager->expects($this->any())
            ->method('getTickets')->willReturn($tickets); 
        
        return $roomManager;
    }
    
    public function testManageTicketRoom() {
        $roomId = 3;
        $userId = 1;
        $cid = 5;
        
        $citizen = new Citizen();
        $citizen->setBirthDate(new \DateTime("1978-08-21"));
         
        $rooms = array();
        $rooms[] = $this->createRoom(1, 2, $roomId);
        
        $now1 = new \DateTime("2020-02-01");
        $resp1 = Citizen::createNewTicketsRoom($citizen, $roomId,
                    $rooms, $userId, $cid, $now1);
        
        
        $resp1Ticket = $resp1[0];
        $this->assertEquals("TK1", $resp1Ticket->getName());
        
        $now2 = new \DateTime("2020-03-02");
        $resp2 = Citizen::createNewTicketsRoom($citizen, $roomId,
                    $rooms, $userId, $cid, $now2);
        
        $respTicket = $resp2[0];
        $this->assertEquals("TK2", $respTicket->getName());
    }
    
    public function testRemoveAllTicketsroomNotIncluded() {
        $roomId = 3;
        $userId = 1;
        $cid = 5;
        
        $citizen = new Citizen();
        $citizen->setBirthDate(new \DateTime("1978-08-21"));
         
        $rooms = array();
        $rooms[] = $this->createRoom(1, 2, $roomId);
        
        $now1 = new \DateTime("2020-02-01");
        $resp1 = Citizen::createNewTicketsRoom($citizen, $roomId,
                    $rooms, $userId, $cid, $now1);
        
        $resp1Ticket = $resp1[0];
        $this->assertEquals("TK1", $resp1Ticket->getName());
        
        $tickets1 = $citizen->getTicketsroom();
        $this->assertEquals(0, count($tickets1));
        
        $citizen->removeAllTicketsroomNotIncluded([$roomId]);
        
        $tickets2 = $citizen->getTicketsroom();
        $this->assertEquals(0, count($tickets2));
        
        
    }
    
    public function testUpdateTicketRoomADDnew() {
        $roomId1 = 3;
        $userId = 1;
        $cid = 5;
        
        $citizen = new Citizen();
        $citizen->setBirthDate(new \DateTime("1978-08-21"));
         
        $rooms1 = array();
        $rooms1[] = $this->createRoom(1, 2, $roomId1);
        
        $now1 = new \DateTime("2020-02-01");
        $resp1 = Citizen::createNewTicketsRoom($citizen, $roomId1,
                    $rooms1, $userId, $cid, $now1);
        $citizen->updateTicketRoom($resp1, $roomId1);
        
        $tickets1 = $citizen->getTicketsroom();
        $this->assertEquals(1, count($tickets1));
        
        $resp1Ticket = $tickets1[0];
        $this->assertEquals("TK1", $resp1Ticket->getName());
    }
    
    public function testUpdateTicketRoomADDnewRemoveOld() {
        $roomId1 = 3;
        $userId = 1;
        $cid = 5;
        
        $citizen = new Citizen();
        $citizen->setBirthDate(new \DateTime("1978-08-21"));
         
        $rooms1 = array();
        $rooms1[] = $this->createRoom(1, 2, $roomId1);
        
        $now1 = new \DateTime("2020-02-01");
        $resp1 = Citizen::createNewTicketsRoom($citizen, $roomId1,
                    $rooms1, $userId, $cid, $now1);
        $citizen->updateTicketRoom($resp1, $roomId1);
        
        $tickets1 = $citizen->getTicketsroom();
        $this->assertEquals(1, count($tickets1));
        
        $resp1Ticket = $tickets1->first();
        $this->assertEquals("TK1", $resp1Ticket->getName());
        
        
        $now2 = new \DateTime("2020-03-02");
        $resp2 = Citizen::createNewTicketsRoom($citizen, $roomId1,
                    $rooms1, $userId, $cid, $now2);
        $citizen->updateTicketRoom($resp2, $roomId1);
        
        $tickets2 = $citizen->getTicketsroom();
        $this->assertEquals(1, count($tickets2));
        $resp2Ticket = $tickets2->first();
        $this->assertEquals("TK2", $resp2Ticket->getName()); 
    }
    
    
    public function testUpdateTicketRoomNoUpdate() {
        $roomId1 = 3;
        $userId = 1;
        $cid = 5;
        
        $citizen = new Citizen();
        $citizen->setBirthDate(new \DateTime("1978-08-21"));
         
        $rooms1 = array();
        $rooms1[] = $this->createRoom(1, 2, $roomId1);
        
        $now1 = new \DateTime("2020-02-01");
        $resp1 = Citizen::createNewTicketsRoom($citizen, $roomId1,
                    $rooms1, $userId, $cid, $now1);
        $citizen->updateTicketRoom($resp1, $roomId1);
        
        $tickets1 = $citizen->getTicketsroom();
        $this->assertEquals(1, count($tickets1));
        
        $resp1Ticket = $tickets1->first();
        //var_dump($tickets1); die(); 
        $this->assertEquals("TK1", $resp1Ticket->getName());
        
        
        $now2 = new \DateTime("2020-02-03");
        $resp2 = Citizen::createNewTicketsRoom($citizen, $roomId1,
                    $rooms1, $userId, $cid, $now2);
        $citizen->updateTicketRoom($resp2, $roomId1);
        
        $tickets2 = $citizen->getTicketsroom();
        $this->assertEquals(1, count($tickets2));
        $resp2Ticket = $tickets2->first();
        $this->assertEquals("TK1", $resp2Ticket->getName()); 
        
        
    }
    
    public function testUpdateTicketRoomNewAge() {
        $roomId1 = 3;
        $userId = 1;
        $cid = 5;
        
        $citizen = new Citizen();
        $citizen->setBirthDate(new \DateTime("1978-08-21"));
         
        $rooms1 = array();
        $rooms1[] = $this->createRoom(1, 2, $roomId1);
        
        $now1 = new \DateTime("2020-02-01");
        $resp1 = Citizen::createNewTicketsRoom($citizen, $roomId1,
                    $rooms1, $userId, $cid, $now1);
        $citizen->updateTicketRoom($resp1, $roomId1);
        
        $tickets1 = $citizen->getTicketsroom();
        $this->assertEquals(1, count($tickets1));
        
        $resp1Ticket = $tickets1->first();
        //var_dump($tickets1); die(); 
        $this->assertEquals("TK1", $resp1Ticket->getName());
        
        $citizen->setBirthDate(new \DateTime("2019-08-21"));
        
        
        $resp2 = Citizen::createNewTicketsRoom($citizen, $roomId1,
                    $rooms1, $userId, $cid, $now1);
        $citizen->updateTicketRoom($resp2, $roomId1);
        
        $tickets2 = $citizen->getTicketsroom();
        $this->assertEquals(1, count($tickets2));
        $resp2Ticket = $tickets2->first();
        $this->assertEquals("TK1 bimbi", $resp2Ticket->getName()); 
    }
    
    
}
