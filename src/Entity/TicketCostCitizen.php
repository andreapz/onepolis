<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketCostCitizenRepository")
 */
class TicketCostCitizen
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
    private $bookDate;

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
    private $ttid;
    
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
    private $cid;

    /**
     * @ORM\ManyToOne(targetEntity="TicketCost")
     * @ORM\JoinColumn(name="ticket_cost", referencedColumnName="id")
     */
    private $ticketcost;
    
    /**
     * @ORM\ManyToOne(targetEntity="Citizen", inversedBy="ticketsevent")
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

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
    }

    public function setUpdateDate(\DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

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

    public function getTicketcost(): ?TicketCost
    {
        return $this->ticketcost;
    }

    public function setTicketcost(?TicketCost $ticketcost): self
    {
        $this->ticketcost = $ticketcost;

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
    
    public static function create($event, $ticketTypeid, 
            TicketCost $ticket, Citizen $citizen, $userId, 
            $citizenId){
        $userticket = new TicketCostCitizen();
        $userticket->setName($ticket->getName());
        $userticket->setPrice($ticket->getPrice());
        $userticket->setBookDate(new \DateTime("now"));
        $userticket->setOrderDate((new \DateTime()));
        $userticket->setEvent($event);
        $userticket->setTtid($ticketTypeid);
        $userticket->setUid($userId);
        $userticket->setUpdateDate((new \DateTime()));
        $userticket->setTicketcost($ticket);
        $userticket->setCitizen($citizen);
        //set Citizen ID
        $userticket->setCid($citizenId);
        return $userticket;
    }

    public function getBookDate(): ?\DateTimeInterface
    {
        return $this->bookDate;
    }

    public function setBookDate(\DateTimeInterface $bookDate): self
    {
        $this->bookDate = $bookDate;

        return $this;
    }

    public function getTtid(): ?int
    {
        return $this->ttid;
    }

    public function setTtid(int $ttid): self
    {
        $this->ttid = $ttid;

        return $this;
    }
    
    public function isNewPrice($ticket) {
        return $this->getPrice() != $ticket->getPrice()
                && $this->getTtid() == $ticket->getTtid();
    }
}
