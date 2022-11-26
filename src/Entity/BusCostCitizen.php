<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BusCostCitizenRepository")
 */
class BusCostCitizen
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
    private $bid;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $cid;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $transport;
    
    /**
     * @ORM\ManyToOne(targetEntity="BusCost")
     * @ORM\JoinColumn(name="bus_cost", referencedColumnName="id")
     */
    private $buscost;
    
    /**
     * @ORM\ManyToOne(targetEntity="Citizen", inversedBy="ticketsbus")
     * @ORM\JoinColumn(name="citizen", referencedColumnName="id")
     */
    private $citizen;
    
    public static function create(Bus $bus, BusCost $ticket,
        Citizen $citizen, $eventId, $transportId, 
            $userId, $citizenId){
        $userticket = new BusCostCitizen();
        $userticket->setName($ticket->getName());
        $userticket->setPrice($ticket->getPrice());
        $userticket->setOrderDate((new \DateTime()));
        $userticket->setUid($userId);
        $userticket->setBuscost($ticket);
        $userticket->setCitizen($citizen);
        $userticket->setEvent($eventId);
        $userticket->setUpdateDate((new \DateTime()));
        $userticket->setTransport($transportId);
        //set Bus ID
        $userticket->setBid($bus->getId());
        //set Citizen ID
        $userticket->setCid($citizenId);
        return $userticket;
    }
    
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

    public function getBid(): ?int
    {
        return $this->bid;
    }

    public function setBid(int $bid): self
    {
        $this->bid = $bid;

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

    public function getBuscost(): ?BusCost
    {
        return $this->buscost;
    }

    public function setBuscost(?BusCost $buscost): self
    {
        $this->buscost = $buscost;

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

    public function getTransport(): ?int
    {
        return $this->transport;
    }

    public function setTransport(int $transport): self
    {
        $this->transport = $transport;

        return $this;
    }
    
    public function isNewPrice($ticket) {
        return $this->getPrice() != $ticket->getPrice()
                && $this->getBid() == $ticket->getBid();
    }
}
