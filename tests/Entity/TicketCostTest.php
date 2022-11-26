<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Test\Entity;

use App\Entity\TicketCost;
use App\Entity\Citizen;
use App\Entity\TicketType;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class TicketCostTest extends TestCase {

    public static function createTicket($id, $price, $name,
            $minAge, $maxAge, $initialDate, $endDate, $ticketTypeManager,
            $instance) {
        $ticketManager = $instance->createMock(TicketCost::class);
        $ticketManager->expects($instance->any())
                ->method('getId')->willReturn($id);
        $ticketManager->expects($instance->any())
                ->method('getPrice')->willReturn($price);
        $ticketManager->expects($instance->any())
                ->method('getName')->willReturn($name);
        $ticketManager->expects($instance->any())
                ->method('getMinAge')->willReturn($minAge);
        $ticketManager->expects($instance->any())
                ->method('getMaxAge')->willReturn($maxAge);
        $ticketManager->expects($instance->any())
                ->method('getBookInitDate')->willReturn($initialDate);
        $ticketManager->expects($instance->any())
                ->method('getBookEndDate')->willReturn($endDate);
        $ticketManager->expects($instance->any())
                ->method('getTicketType')->willReturn($ticketTypeManager);

        return $ticketManager;
    }

    public static function createTicket1($ticketTypeManager, $instance) {
        return TicketCostTest::createTicket(1, "11,00", "TK1", 10, 100,
                        new \DateTime("2020-01-01"),
                        new \DateTime("2020-03-01"),
                        $ticketTypeManager, $instance);
    }

    public static function createTicket2($ticketTypeManager, $instance) {
        return TicketCostTest::createTicket(2, "7,00", "TK1b", 0, 9,
                        new \DateTime("2020-01-01"),
                        new \DateTime("2020-05-01"),
                        $ticketTypeManager, $instance);
    }

    public static function createTicket3($ticketTypeManager, $instance) {
        return TicketCostTest::createTicket(3, "22,00", "TK2", 10, 100,
                        new \DateTime("2020-03-02"),
                        new \DateTime("2020-05-01"),
                        $ticketTypeManager, $instance);
    }

    public static function createTicket4($ticketTypeManager, $instance) {
        return TicketCostTest::createTicket(1, "13,00", "TC1", 0, 100,
                        new \DateTime("2020-01-01"),
                        new \DateTime("2020-03-01"),
                        $ticketTypeManager, $instance);
    }

    public static function createTicketType1($ticketTypeId, $instance) {
        $ticketTypeManager1 = $instance->createMock(TicketType::class);
        $ticketTypeManager1->expects($instance->any())
                ->method('getId')->willReturn($ticketTypeId);
        
        $ticket1Manager = $instance->createTicket1($ticketTypeManager1, $instance);
        $ticket2Manager = $instance->createTicket2($ticketTypeManager1, $instance);
        
        $tickets = new ArrayCollection();
        $tickets[] = $ticket1Manager;
        $tickets[] = $ticket2Manager;
       
        $ticketTypeManager1->expects($instance->any())
                ->method('getTickets')->willReturn($tickets);

        return $ticketTypeManager1;
    }
    
    public static function createTicketType2($ticketTypeId, $instance) {
        $ticketTypeManager = $instance->createMock(TicketType::class);
        $ticketTypeManager->expects($instance->any())
                ->method('getId')->willReturn($ticketTypeId);
        
        $ticket4Manager = TicketCostTest::createTicket4($ticketTypeManager, $instance);

        $tickets = new ArrayCollection();
        $tickets[] = $ticket4Manager;
        
        $ticketTypeManager->expects($instance->any())
                ->method('getTickets')->willReturn($tickets);

        return $ticketTypeManager;
    }

    public function testTicketIsValid() {
        $ticketTypeId1 = 3;
        $ticketTypeId2 = 4;
        $ticketTypeManager1 = TicketCostTest::createTicketType1($ticketTypeId1, $this);
        $ticketTypeManager2 = TicketCostTest::createTicketType1($ticketTypeId2, $this);
        $ticket1Manager = TicketCostTest::createTicket1($ticketTypeManager1, $this);
        $ticket2Manager = TicketCostTest::createTicket2($ticketTypeManager1, $this);
        $ticket4Manager = TicketCostTest::createTicket4($ticketTypeManager2, $this);

        $citizen = new Citizen();
        $citizen->setBirthDate(new \DateTime("1978-08-21"));

        $now1 = new \DateTime("2020-02-01");

        $this->assertEquals(true, TicketCost::isTicketValid($ticket1Manager,
                        $ticketTypeId1, $now1, $citizen->getBirthDate()));

        $this->assertEquals(false, TicketCost::isTicketValid($ticket2Manager,
                        $ticketTypeId1, $now1, $citizen->getBirthDate()));

        $this->assertEquals(true, TicketCost::isTicketValid($ticket4Manager,
                        $ticketTypeId2, $now1, $citizen->getBirthDate()));


        $now2 = new \DateTime("2020-03-02");

        $this->assertEquals(false, TicketCost::isTicketValid($ticket1Manager,
                        $ticketTypeId1, $now2, $citizen->getBirthDate()));

        $this->assertEquals(false, TicketCost::isTicketValid($ticket2Manager,
                        $ticketTypeId1, $now2, $citizen->getBirthDate()));

        
        $citizen->setBirthDate(new \DateTime("2019-08-21"));

        $this->assertEquals(false, TicketCost::isTicketValid($ticket1Manager,
                        $ticketTypeId1, $now2, $citizen->getBirthDate()));

        $this->assertEquals(true, TicketCost::isTicketValid($ticket2Manager,
                        $ticketTypeId1, $now2, $citizen->getBirthDate()));

    }

    public function testCreateUpdateAddingTicket() {
        $eventId = 1;
        $ticketTypeId1 = 3;
        $ticketTypeId2 = 4;
        
        $ticketTypeManager1 = TicketCostTest::createTicketType1($ticketTypeId1, $this);
        $ticketTypeManager2 = TicketCostTest::createTicketType2($ticketTypeId2, $this);
        
        $userId = 1;
        $cid = 5;
    
        $citizen = new Citizen();
        $citizen->setBirthDate(new \DateTime("1978-08-21"));

        $now1 = new \DateTime("2020-02-01");
        
        $resp1 = Citizen::createNewTicketEvent($eventId,
                        $citizen, $ticketTypeManager1, $userId, $cid,
                        $now1);
        
        $this->assertNotNull($resp1);
        $this->assertEquals("TK1", $resp1->getName());
        $this->assertNotEquals("TK2", $resp1->getName());

        $citizen->updateTicketEvent($resp1);
        $ticketTicketCostCitizen1 = $citizen->getTicketsevent();
        $this->assertNotNull($ticketTicketCostCitizen1);
        $this->assertEquals("TK1", $ticketTicketCostCitizen1->getName());
        $this->assertNotEquals("TK2", $ticketTicketCostCitizen1->getName());
        
        $resp2 = Citizen::createNewTicketEvent($eventId,
                        $citizen, $ticketTypeManager2, $userId, $cid,
                        $now1);

        $this->assertNotNull($resp2);
        $this->assertEquals("TC1", $resp2->getName());
        $this->assertNotEquals("TK2", $resp2->getName());
        
        $citizen->updateTicketEvent($resp2);
        
        $ticketTicketCostCitizen2 = $citizen->getTicketsevent();
        $this->assertNotNull($ticketTicketCostCitizen2);
        $this->assertEquals("TC1", $ticketTicketCostCitizen2->getName());
        $this->assertNotEquals("TK2", $ticketTicketCostCitizen2->getName());
        
        //----------------------
        $citizen->setBirthDate(new \DateTime("2019-08-21"));
        
        $resp3 = Citizen::createNewTicketEvent($eventId,
                        $citizen, $ticketTypeManager1, $userId, $cid,
                        $now1);
        
        $this->assertNotNull($resp3);
        $this->assertEquals("TK1b", $resp3->getName());
        $this->assertNotEquals("TK2", $resp3->getName());
        
        $citizen->updateTicketEvent($resp3);
        
        $ticketTicketCostCitizen3 = $citizen->getTicketsevent();
        $this->assertNotNull($ticketTicketCostCitizen3);
        $this->assertEquals("TK1b", $ticketTicketCostCitizen3->getName());
        $this->assertNotEquals("TK2", $ticketTicketCostCitizen3->getName());

        $resp4 = Citizen::createNewTicketEvent($eventId,
                        $citizen, $ticketTypeManager2, $userId, $cid,
                        $now1);

        $this->assertNotNull($resp4);
        $this->assertEquals("TC1", $resp4->getName());
        $this->assertNotEquals("TK2", $resp4->getName());
        
        $citizen->updateTicketEvent($resp4);
        
        $ticketTicketCostCitizen4 = $citizen->getTicketsevent();
        
        $this->assertNotNull($ticketTicketCostCitizen4);
        $this->assertEquals("TC1", $ticketTicketCostCitizen4->getName());
        $this->assertNotEquals("TK2", $ticketTicketCostCitizen4->getName());
        
        
        
        //----------------------
        $citizen->setBirthDate(new \DateTime("2019-08-21"));
        
        $resp5 = Citizen::createNewTicketEvent($eventId,
                        $citizen, $ticketTypeManager1, $userId, $cid,
                        $now1);
        $this->assertNotNull($resp5);
        $this->assertEquals("TK1b", $resp5->getName());
        $this->assertNotEquals("TK2", $resp5->getName());
        
        $citizen->updateTicketEvent($resp5);
        
        $ticketTicketCostCitizen5 = $citizen->getTicketsevent();
        $this->assertNotNull($ticketTicketCostCitizen5);
        $this->assertEquals("TK1b", $ticketTicketCostCitizen5->getName());
        $this->assertNotEquals("TK2", $ticketTicketCostCitizen5->getName());
    }
    
    public function testRegisterTicket() {
        $eventId = 1;
        $ticketTypeId1 = 3;
        $ticketTypeId2 = 4;
        
        $ticketTypeManager1 = TicketCostTest::createTicketType1($ticketTypeId1, $this);
        $ticketTypeManager2 = TicketCostTest::createTicketType2($ticketTypeId2, $this);

        $ticketTypeManagers = array();
        $ticketTypeManagers[] = $ticketTypeManager1;
        $ticketTypeManagers[] = $ticketTypeManager2;

        $userId = 1;
        $cid = 5;
        
        $now1 = new \DateTime("2020-02-01");
        
        $citizen = new Citizen();
        $citizen->setBirthDate(new \DateTime("1978-08-21"));

        $citizen->registerTicketEvent($ticketTypeManagers, $ticketTypeId1, 
                $eventId, $userId, $cid, $now1);
        $ticketTicketCostCitizen1 = $citizen->getTicketsevent();
        $this->assertNotNull($ticketTicketCostCitizen1);
        $this->assertEquals("TK1", $ticketTicketCostCitizen1->getName());
        $this->assertNotEquals("TK2", $ticketTicketCostCitizen1->getName());
        
        $citizen->registerTicketEvent($ticketTypeManagers, $ticketTypeId2, 
                $eventId, $userId, $cid, $now1);
        $ticketTicketCostCitizen2 = $citizen->getTicketsevent();
        $this->assertNotNull($ticketTicketCostCitizen2);
        $this->assertNotEquals("TK1", $ticketTicketCostCitizen2->getName());
        $this->assertEquals("TC1", $ticketTicketCostCitizen2->getName());
    }
    
}
