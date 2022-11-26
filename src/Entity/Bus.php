<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BusRepository")
 */
class Bus
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
     * @ORM\OneToMany(targetEntity="BusCost", cascade={"persist"}, mappedBy="bus")
    */
    private $tickets;
    
    /**
     * @ORM\ManyToOne(targetEntity="Transport", inversedBy="transports")
     * @ORM\JoinColumn(name="transport", referencedColumnName="id")
     */
    private $transport;

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

    /**
     * @return Collection|BusCost[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(BusCost $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setBus($this);
        }

        return $this;
    }

    public function removeTicket(BusCost $ticket): self
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if ($ticket->getBus() === $this) {
                $ticket->setBus(null);
            }
        }

        return $this;
    }

    public function getTransport(): ?Transport
    {
        return $this->transport;
    }

    public function setTransport(?Transport $transport): self
    {
        $this->transport = $transport;

        return $this;
    }
    
    public static function retrieve($buses, $busId) {
        foreach ($buses as $bus) {
            if ($bus->getId() == $busId) {
                return $bus;
            }
        }
        return null;
    }
}
