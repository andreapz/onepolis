<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketCostRepository")
 */
class TicketCost
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
    private $bookInitDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $bookEndDate;

    /**
     * @ORM\ManyToOne(targetEntity="TicketType", inversedBy="tickets")
     * @ORM\JoinColumn(name="ticket_type", referencedColumnName="id")
     */
    private $ticketType;
    
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

    public function getBookInitDate(): ?\DateTimeInterface
    {
        return $this->bookInitDate;
    }

    public function setBookInitDate(\DateTimeInterface $bookDate): self
    {
        $this->bookInitDate = $bookDate;

        return $this;
    }
    
    public function getBookEndDate(): ?\DateTimeInterface
    {
        return $this->bookEndDate;
    }

    public function setBookEndDate(\DateTimeInterface $bookDate): self
    {
        $this->bookEndDate = $bookDate;

        return $this;
    }


    function getBookDateFormatted() {
        return $this->bookDate->format('d-m-Y');
    }
    
    public static function isTicketValid($ticket, int $ticketTypeId, $now,
            \DateTimeInterface $oldDate) {
        if ($ticket->getTicketType()->getId() == $ticketTypeId) {
            $interval = $oldDate->diff($now);
            $age = intval($interval->format('%y'));
            
            if($age >= $ticket->getMinAge()
                    && $age <= $ticket->getMaxAge()
                    && $now >= $ticket->getBookInitDate()
                    && $now <= $ticket->getBookEndDate()) {
                
                return TRUE;
            }
        }
        return FALSE;
    }

    public function getTicketType(): ?TicketType
    {
        return $this->ticketType;
    }

    public function setTicketType(?TicketType $ticketType): self
    {
        $this->ticketType = $ticketType;

        return $this;
    }
}
