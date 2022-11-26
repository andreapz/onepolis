<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 */
class Task
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $payed;
    
    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="tasks")
     * @ORM\JoinColumn(name="event", referencedColumnName="id")
     */
    private $event;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $ueid;
   
    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $utid;
    
    /**
     * @var int
     *
     * @ORM\Column(name="uid", type="integer")
     */
    private $uid; 
    
    /**
     * @var int
     *
     * @ORM\Column(name="ordered", type="integer")
     */
    private $ordered; 
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $orderedDate;
    
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $code;
    
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $amount;
    
    /**
     * @ORM\OneToMany(targetEntity="Citizen", cascade={"persist"}, mappedBy="task")
    */
    private $citizens;
    
    /**
     * @ORM\OneToMany(targetEntity="CitizenPayment", cascade={"persist"}, mappedBy="task")
    */
    private $payments;
    
    public function __construct()
    {
        $this->payments = new ArrayCollection();
        $this->citizens = new ArrayCollection();
    }
    
    
    public function getId(): ?int
    {
        return $this->id;
    }

    function getUeid() {
        return $this->ueid;
    }

    function getUtid() {
        return $this->utid;
    }

    function setUeid($ueid) {
        $this->ueid = $ueid;
    }

    function setUtid($utid) {
        $this->utid = $utid;
    }
    
    public function getPayed()
    {
        return $this->payed;
    }

    public function setPayed($payed): self
    {
        $this->payed = $payed;

        return $this;
    }

    /**
     * @return Collection|CitizenPayment[]
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(CitizenPayment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments[] = $payment;
            $payment->setTask($this);
        }

        return $this;
    }

    public function removePayment(CitizenPayment $payment): self
    {
        if ($this->payments->contains($payment)) {
            $this->payments->removeElement($payment);
            // set the owning side to null (unless already changed)
            if ($payment->getTask() === $this) {
                $payment->setTask(null);
            }
        }

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return Collection|Citizen[]
     */
    public function getCitizens(): Collection
    {
        return $this->citizens;
    }

    public function addCitizen(Citizen $citizen): self
    {
        if (!$this->citizens->contains($citizen)) {
            $this->citizens[] = $citizen;
            $citizen->setTask($this);
        }

        return $this;
    }

    public function removeCitizen(Citizen $citizen): self
    {
        if ($this->citizens->contains($citizen)) {
            $this->citizens->removeElement($citizen);
            // set the owning side to null (unless already changed)
            if ($citizen->getTask() === $this) {
                $citizen->setTask(null);
            }
        }

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
    
    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): self
    {
        $this->task = $task;

        return $this;
    }
    
    function getRestaurantMeals() {
        $meals = array();
        foreach ($this->event->getRestaurants() as $restaurant) {
            foreach ($restaurant->getMeals() as $meal) {
                array_push($meals, $meal);
            }
        }
        return $meals;
    }
    
    function getHotelRooms() {
        $rooms = array();
        foreach ($this->event->getHotels() as $hotel) {
            foreach ($hotel->getRooms() as $roombase) {
                foreach ($roombase->getRooms() as $room) {
                    array_push($rooms, $room);    
                }
            }
        }
        return $rooms;
    }
    
    function getTransportBuses() {
        $buses = array();
        foreach ($this->event->getTransports() as $transport) {
            foreach ($transport->getTransports() as $bus) {
                array_push($buses, $bus);    
            }
        }
        return $buses;
    }
    
    function getTickets() {
        $tickets = array();
        foreach ($this->event->getTickets() as $ticket) {
            array_push($tickets, $ticket);    
        }
        return $tickets;
    }

    public function getOrdered(): ?int
    {
        return $this->ordered;
    }

    public function setOrdered(int $ordered): self
    {
        $this->ordered = $ordered;

        return $this;
    }

    public function getOrderedDate(): ?\DateTimeInterface
    {
        return $this->orderedDate;
    }

    public function setOrderedDate(\DateTimeInterface $orderedDate): self
    {
        $this->orderedDate = $orderedDate;

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
