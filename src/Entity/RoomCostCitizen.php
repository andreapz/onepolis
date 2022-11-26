<?php

namespace App\Entity;

use App\Entity\Citizen;
use App\Entity\Room;
use App\Entity\RoomCost;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoomCostCitizenRepository")
 */
class RoomCostCitizen
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $orderDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $event;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $uid;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $updateDate;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $rbid;

    
    /**
     * @ORM\Column(type="integer")
     */
    private $rid;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $cid;
    
    /**
     * @ORM\ManyToOne(targetEntity="RoomCost")
     * @ORM\JoinColumn(name="room_cost", referencedColumnName="id")
     */
    private $roomcost;
    
    /**
     * @ORM\ManyToOne(targetEntity="Citizen", inversedBy="ticketsroom")
     * @ORM\JoinColumn(name="citizen", referencedColumnName="id")
     */
    private $citizen;
    
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeInterface $orderDate): self
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getEvent(): ?int
    {
        return $this->event;
    }

    public function setEvent(int $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getRoomcost(): ?RoomCost
    {
        return $this->roomcost;
    }

    public function setRoomcost(?RoomCost $roomcost): self
    {
        $this->roomcost = $roomcost;

        return $this;
    }

    public function getCitizen(): ?Citizen
    {
        return $this->citizen;
    }

    public function setCitizen(?Citizen $citizen): self
    {
        $this->citizen = $citizen;

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
    }

    public function setUpdateDate(\DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    public function getRid(): ?int
    {
        return $this->rid;
    }

    public function setRid(int $rid): self
    {
        $this->rid = $rid;

        return $this;
    }

    public function getCid(): ?int
    {
        return $this->cid;
    }

    public function setCid(int $cid): self
    {
        $this->cid = $cid;

        return $this;
    }
    
    public function getRbid(): ?int
    {
        return $this->rbid;
    }

    public function setRbid(int $rbid): self
    {
        $this->rbid = $rbid;

        return $this;
    }
    
    public static function create($eventId, $roomBaseId, $roomId, 
            RoomCost $ticket, Citizen $citizen, $userId, $id) {
        $userticket = new RoomCostCitizen();
        $userticket->setName($ticket->getName());
        $userticket->setPrice($ticket->getPrice());
        $userticket->setOrderDate((new \DateTime()));
        $userticket->setUid($userId);
        $userticket->setRoomcost($ticket);
        $userticket->setEvent($eventId);
        $userticket->setCitizen($citizen);
        $userticket->setUpdateDate((new \DateTime()));
        $userticket->setRbid($roomBaseId);
        $userticket->setRid($roomId);
        //set Citizen ID
        $userticket->setCid($id);
        return $userticket;
    }
    
    public function isEqual($ticket) {
        return $this->name == $ticket->getName()
                && $this->getPrice() == $ticket->getPrice()
                && $this->getRid() == $ticket->getRid()
                && $this->getRoomcost() == $ticket->getRoomcost();
    }
    
    public function isNewPrice($ticket) {
        return $this->getPrice() != $ticket->getPrice()
                && $this->getRid() == $ticket->getRid();
    }
}
