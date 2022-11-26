<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Test\Entity;

use App\Entity\BusCost;
use App\Entity\Citizen;
use App\Entity\Bus;
use App\Entity\Transport;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class TransportCostTest extends TestCase {

    public static function createTransport($id, $instance) {
        $transportManager = $instance->createMock(Transport::class);
        $transportManager->expects($instance->any())
                ->method('getId')->willReturn($id);
        return $transportManager;
    }
    
    public static function createTicket($id, $price, $name,
            $minAge, $maxAge, $initialDate, $endDate, $busManager,
            $instance) {
        $ticketManager = $instance->createMock(BusCost::class);
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
                ->method('getInitialDate')->willReturn($initialDate);
        $ticketManager->expects($instance->any())
                ->method('getEndDate')->willReturn($endDate);
        $ticketManager->expects($instance->any())
                ->method('getBus')->willReturn($busManager);
        
        return $ticketManager;
    }

    public static function createTicket1($busManager, $instance) {
        return TransportCostTest::createTicket(1, "11,00", "TK1", 10, 100,
                        new \DateTime("2020-01-01"),
                        new \DateTime("2020-03-01"),
                        $busManager, $instance);
    }

    public static function createTicket2($busManager, $instance) {
        return TransportCostTest::createTicket(2, "7,00", "TK1b", 0, 9,
                        new \DateTime("2020-01-01"),
                        new \DateTime("2020-05-01"),
                        $busManager, $instance);
    }

    public static function createTicket3($busManager, $instance) {
        return TransportCostTest::createTicket(3, "22,00", "TK2", 10, 100,
                        new \DateTime("2020-03-02"),
                        new \DateTime("2020-05-01"),
                        $busManager, $instance);
    }

    public static function createTicket4($busManager, $instance) {
        return TransportCostTest::createTicket(1, "13,00", "TC1", 0, 100,
                        new \DateTime("2020-01-01"),
                        new \DateTime("2020-03-01"),
                        $busManager, $instance);
    }

    public static function createBus1($busId, $instance) {
        $busManager1 = $instance->createMock(Bus::class);
        //$mealManager->expects($this->any())
        //  ->method('getEid')->willReturn($eventId);
        $busManager1->expects($instance->any())
                ->method('getId')->willReturn($busId);
        
        $ticket1Manager = $instance->createTicket1($busManager1, $instance);
        $ticket2Manager = $instance->createTicket2($busManager1, $instance);
        
        $tickets = new ArrayCollection();
        $tickets[] = $ticket1Manager;
        $tickets[] = $ticket2Manager;
       
        $busManager1->expects($instance->any())
                ->method('getTickets')->willReturn($tickets);
        
        $busManager1->expects($instance->any())
               ->method('getTransport')->willReturn(
                       TransportCostTest::createTransport(1, $instance));

        return $busManager1;
    }
    
    public static function createBus2($busId, $instance) {
        $busManager = $instance->createMock(Bus::class);
        
        $busManager->expects($instance->any())
                ->method('getId')->willReturn($busId);
        
        $ticket4Manager = TransportCostTest::createTicket4($busManager, $instance);

        $tickets = new ArrayCollection();
        $tickets[] = $ticket4Manager;
        
        $busManager->expects($instance->any())
                ->method('getTickets')->willReturn($tickets);

        $busManager->expects($instance->any())
               ->method('getTransport')->willReturn(
                       TransportCostTest::createTransport(2, $instance));

        
        return $busManager;
    }

    public function testTicketIsValid() {
        $busId1 = 3;
        $busId2 = 4;
        $busManager1 = TransportCostTest::createBus1($busId1, $this);
        $busManager2 = TransportCostTest::createBus1($busId2, $this);
        $ticket1Manager = TransportCostTest::createTicket1($busManager1, $this);
        $ticket2Manager = TransportCostTest::createTicket2($busManager1, $this);
        $ticket4Manager = TransportCostTest::createTicket4($busManager2, $this);

        $citizen = new Citizen();
        $citizen->setBirthDate(new \DateTime("1978-08-21"));

        $now1 = new \DateTime("2020-02-01");

        $this->assertEquals(true, BusCost::isTicketValid($ticket1Manager,
                        $busId1, $now1, $citizen->getBirthDate()));

        $this->assertEquals(false, BusCost::isTicketValid($ticket2Manager,
                        $busId1, $now1, $citizen->getBirthDate()));

        $this->assertEquals(true, BusCost::isTicketValid($ticket4Manager,
                        $busId2, $now1, $citizen->getBirthDate()));

        
        $now2 = new \DateTime("2020-03-02");

        $this->assertEquals(false, BusCost::isTicketValid($ticket1Manager,
                        $busId1, $now2, $citizen->getBirthDate()));

        $this->assertEquals(false, BusCost::isTicketValid($ticket2Manager,
                        $busId1, $now2, $citizen->getBirthDate()));

        
        $citizen->setBirthDate(new \DateTime("2019-08-21"));

        $this->assertEquals(false, BusCost::isTicketValid($ticket1Manager,
                        $busId1, $now2, $citizen->getBirthDate()));

        $this->assertEquals(true, BusCost::isTicketValid($ticket2Manager,
                        $busId1, $now2, $citizen->getBirthDate()));

    }

    public function testCreateNewTicket() {
        $eventId = 1;
        $busId1 = 3;
        
        $transportId = 1;
        $busManager1 = TransportCostTest::createBus1($busId1, $this);
        
        $userId = 1;
        $cid = 5;

        $citizen = new Citizen();
        $citizen->setBirthDate(new \DateTime("1978-08-21"));

        $now1 = new \DateTime("2020-02-01");
        $resp1 = Citizen::createNewTicketsBus($eventId, $transportId,
                        $citizen, $busManager1, $userId, $cid,
                        $now1);

        $this->assertNotNull($resp1);
        $this->assertEquals("TK1", $resp1->getName());
        $this->assertNotEquals("TK2", $resp1->getName());

        $citizen->setBirthDate(new \DateTime("2019-08-21"));
        $resp2 = Citizen::createNewTicketsBus($eventId, $transportId,
                        $citizen, $busManager1, $userId, $cid,
                        $now1);

        $this->assertNotNull($resp2);
        $this->assertEquals("TK1b", $resp2->getName());
        $this->assertNotEquals("TK2", $resp2->getName());
    }

    public function testCreateUpdateAdding2Ticket() {
        $eventId = 1;
        $busId1 = 3;
        $busId2 = 4;
        $transportId = 1;
        $busManager1 = TransportCostTest::createBus1($busId1, $this);
        $busManager2 = TransportCostTest::createBus2($busId2, $this);

        $buses = array();
        $buses[] = $busManager1;
        $buses[] = $busManager2;

        $userId = 1;
        $cid = 5;

        $citizen = new Citizen();
        $citizen->setBirthDate(new \DateTime("1978-08-21"));

        $now1 = new \DateTime("2020-02-01");

        $tickets = array();

        $resp1 = Citizen::createNewTicketsBus($eventId, $transportId,
                        $citizen, $busManager1, $userId, $cid,
                        $now1);
        $tickets[$busManager1->getId()] = $resp1;

        $this->assertNotNull($resp1);
        $this->assertEquals("TK1", $resp1->getName());
        $this->assertNotEquals("TK2", $resp1->getName());


        $resp2 = Citizen::createNewTicketsBus($eventId, $transportId,
                        $citizen, $busManager2, $userId, $cid,
                        $now1);

        $tickets[$busManager2->getId()] = $resp2;
        
        $this->assertNotNull($resp2);
        $this->assertEquals("TC1", $resp2->getName());
        $this->assertNotEquals("TK2", $resp2->getName());
        
        $citizen->updateTicketsBus($tickets);
        
        $ticketBus = $citizen->getTicketsbus();
        $this->assertEquals(2, count($ticketBus));
        
        //----------------------
        $citizen->setBirthDate(new \DateTime("2019-08-21"));
        $tickets2 = array();
        
        $resp3 = Citizen::createNewTicketsBus($eventId, $transportId,
                        $citizen, $busManager1, $userId, $cid,
                        $now1);
        $tickets2[$busManager1->getId()] = $resp3;

        $this->assertNotNull($resp3);
        $this->assertEquals("TK1b", $resp3->getName());
        $this->assertNotEquals("TK2", $resp3->getName());


        $resp4 = Citizen::createNewTicketsBus($eventId, $transportId,
                        $citizen, $busManager2, $userId, $cid,
                        $now1);

        $tickets2[$busManager2->getId()] = $resp4;
        
        $this->assertNotNull($resp4);
        $this->assertEquals("TC1", $resp4->getName());
        $this->assertNotEquals("TK2", $resp4->getName());
        
        $citizen->updateTicketsBus($tickets2);
        
        $ticketrestaurant2 = $citizen->getTicketsbus();
        $this->assertEquals(2, count($ticketrestaurant2));
        //var_dump($ticketrestaurant2);
        //$ticket3 = $ticketrestaurant2->firts();
        
        
        $this->assertEquals(false, $ticketrestaurant2->contains($resp1));
        $this->assertEquals(true, $ticketrestaurant2->contains($resp2));
        $this->assertEquals(true, $ticketrestaurant2->contains($resp3));
        $this->assertEquals(false, $ticketrestaurant2->contains($resp4));
        //$this->assertEquals("TK1b", $resp3->getName());
        
     
        //----------------------
        $citizen->setBirthDate(new \DateTime("2019-08-21"));
        $tickets3 = array();
        
        $resp5 = Citizen::createNewTicketsBus($eventId, $transportId,
                        $citizen, $busManager1, $userId, $cid,
                        $now1);
        $tickets3[$busManager1->getId()] = $resp5;

        $this->assertNotNull($resp5);
        $this->assertEquals("TK1b", $resp5->getName());
        $this->assertNotEquals("TK2", $resp5->getName());
        
        $citizen->updateTicketsBus($tickets3);
        
        $ticketrestaurant3 = $citizen->getTicketsbus();
        $this->assertEquals(1, count($ticketrestaurant3));
        
        
        $this->assertEquals(false, $ticketrestaurant3->contains($resp1));
        $this->assertEquals(false, $ticketrestaurant3->contains($resp2));
        $this->assertEquals(true, $ticketrestaurant3->contains($resp3));
        $this->assertEquals(false, $ticketrestaurant3->contains($resp4));
        $this->assertEquals(false, $ticketrestaurant3->contains($resp5));
        
    }
    
    public function testRegisterTicket() {
        $eventId = 1;
        $busId1 = 3;
        $busId2 = 4;
        
        $busManager1 = TransportCostTest::createBus1($busId1, $this);
        $busManager2 = TransportCostTest::createBus2($busId2, $this);

        $buseManagers = array();
        $buseManagers[] = $busManager1;
        $buseManagers[] = $busManager2;

        $userId = 1;
        $cid = 5;

        $citizen = new Citizen();
        $citizen->setBirthDate(new \DateTime("1978-08-21"));

        $now1 = new \DateTime("2020-02-01");

        $citizen->registerBus([$busManager1], $buseManagers, $eventId, $userId, $cid, $now1);
        $busTickets1 = $citizen->getTicketsbus();

        $this->assertNotNull($busTickets1);
        $this->assertEquals(1, count($busTickets1));
        $busTicket1 = $busTickets1[0];
        $this->assertEquals("TK1", $busTicket1->getName());

        
        $citizen->registerBus([$busManager1, $busManager2], $buseManagers, $eventId, $userId, $cid, $now1);
        $busTickets2 = $citizen->getTicketsbus();

        $this->assertNotNull($busTickets1);
        $this->assertEquals(2, count($busTickets1));
        $busTicket2_1 = $busTickets2[0];
        $this->assertEquals("TK1", $busTicket2_1->getName());
        
        $busTicket2_2 = $busTickets2[1];
        $this->assertEquals("TC1", $busTicket2_2->getName());
    }
    
}
