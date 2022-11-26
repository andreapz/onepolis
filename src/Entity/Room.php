<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoomRepository")
 */
class Room
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
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $total;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $days;

    /**
     * @ORM\Column(type="datetime")
     */
    private $initDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;
    
    /**
     * @ORM\OneToMany(targetEntity="RoomCost", cascade={"persist"}, mappedBy="room")
    */
    private $tickets;
    
    /**
     * @ORM\ManyToOne(targetEntity="RoomBase", inversedBy="rooms")
     * @ORM\JoinColumn(name="room_base", referencedColumnName="id")
     */
    private $parent;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $hid;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $eid;
    
    public function __construct()
    {
        $this->tickets = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return Collection|RoomCost[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(RoomCost $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setRoom($this);
        }

        return $this;
    }

    public function removeTicket(RoomCost $ticket): self
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if ($ticket->getRoom() === $this) {
                $ticket->setRoom(null);
            }
        }

        return $this;
    }

    public function getParent(): ?RoomBase
    {
        return $this->parent;
    }

    public function setParent(?RoomBase $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getDays(): ?int
    {
        return $this->days;
    }

    public function setDays(int $days): self
    {
        $this->days = $days;

        return $this;
    }

    public function getInitDate(): ?\DateTimeInterface
    {
        return $this->initDate;
    }

    public function setInitDate(\DateTimeInterface $initDate): self
    {
        $this->initDate = $initDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }
    
    public function getHid(): ?int
    {
        return $this->hid;
    }

    public function setHid(int $hid): self
    {
        $this->hid = $hid;

        return $this;
    }
    
    public function getEid(): ?int
    {
        return $this->eid;
    }

    public function setEid(int $eid): self
    {
        $this->eid = $eid;

        return $this;
    }
    public static function checkRoomsCount($repository, $eventId) {
        $roomsCount = $repository->findCountByEvent($eventId);
        $roomsCountArray = array();
        foreach ($roomsCount as $roomcount) {
            $roomsCountArray[$roomcount['id']]['id'] = $roomcount['id'];
            $roomsCountArray[$roomcount['id']]['c'] = $roomcount['rcount'];
            $roomsCountArray[$roomcount['id']]['t'] = $roomcount['total'];
        }
        return json_encode($roomsCountArray);
    }
}
