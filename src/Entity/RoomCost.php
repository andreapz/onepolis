<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoomCostRepository")
 */
class RoomCost
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
     * @ORM\Column(type="integer")
     */
    private $minAge;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxAge;

    /**
     * @ORM\Column(type="integer")
     */
    private $total;

    /**
     * @ORM\Column(type="datetime")
     */
    private $initialDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;
    
    /**
     * @ORM\ManyToOne(targetEntity="Room", inversedBy="tickets")
     * @ORM\JoinColumn(name="room", referencedColumnName="id")
     */
    private $room;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $eid;
    
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

    public function getMinAge(): ?int
    {
        return $this->minAge;
    }

    public function setMinAge(int $minAge): self
    {
        $this->minAge = $minAge;

        return $this;
    }

    public function getMaxAge(): ?int
    {
        return $this->maxAge;
    }

    public function setMaxAge(int $maxAge): self
    {
        $this->maxAge = $maxAge;

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

    public function getInitialDate(): ?\DateTimeInterface
    {
        return $this->initialDate;
    }

    public function setInitialDate(\DateTimeInterface $initialDate): self
    {
        $this->initialDate = $initialDate;

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

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

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
    
    function getBookDateFormatted() {
        return $this->bookDate->format('d-m-Y');
    }
    
    public static function isTicketValid($ticket, int $roomId, $now, 
            \DateTimeInterface $oldDate) {
        
        if ($ticket->getRoom()->getId() == $roomId) {
            $interval = $oldDate->diff($now);
            $age = intval($interval->format('%y'));

            if($age >= $ticket->getMinAge()
                    && $age <= $ticket->getMaxAge()
                    && $now >= $ticket->getInitialDate()
                    && $now <= $ticket->getEndDate()) {
                return TRUE;
            }
        }
        return FALSE;
    }
}
