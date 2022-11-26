<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=64)
     */
    private $ueid;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $title;
    
    /**
     * @ORM\Column(type="string", length=10)
     */
    private $slug;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $initialDate;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;
    
    /**
    * @ORM\OneToOne(targetEntity="Address")
    */
    private $address;
    
    /**
    * @ORM\OneToMany(targetEntity="Task", cascade={"persist"}, mappedBy="event")
    */
    private $tasks;
    
    /**
    * @ORM\OneToMany(targetEntity="TicketType", cascade={"persist"}, mappedBy="event")
    */
    private $tickets;
    
    /**
    * @ORM\OneToMany(targetEntity="Restaurant", cascade={"persist"}, mappedBy="event")
    */
    private $restaurants;
    
    /**
    * @ORM\OneToMany(targetEntity="Hotel", cascade={"persist"}, mappedBy="event")
    */
    private $hotels;
    
    /**
    * @ORM\OneToMany(targetEntity="Transport", cascade={"persist"}, mappedBy="event")
    */
    private $transports;

    public function __construct()
    {
        $this->initialDate = new \DateTime();
        $this->tasks = new ArrayCollection();
        $this->tickets = new ArrayCollection();
        $this->restaurants = new ArrayCollection();
        $this->transports = new ArrayCollection();
        $this->hotels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    function getUeid() {
        return $this->ueid;
    }

    function setUeid($ueid) {
        $this->ueid = $ueid;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setEvent($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getEvent() === $this) {
                $task->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TicketType[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(TicketType $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setEvent($this);
        }

        return $this;
    }

    public function removeTicket(TicketType $ticket): self
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if ($ticket->getEvent() === $this) {
                $ticket->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Restaurant[]
     */
    public function getRestaurants(): Collection
    {
        return $this->restaurants;
    }

    public function addRestaurant(Restaurant $restaurant): self
    {
        if (!$this->restaurants->contains($restaurant)) {
            $this->restaurants[] = $restaurant;
            $restaurant->setEvent($this);
        }

        return $this;
    }

    public function removeRestaurant(Restaurant $restaurant): self
    {
        if ($this->restaurants->contains($restaurant)) {
            $this->restaurants->removeElement($restaurant);
            // set the owning side to null (unless already changed)
            if ($restaurant->getEvent() === $this) {
                $restaurant->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Hotel[]
     */
    public function getHotels(): Collection
    {
        return $this->hotels;
    }

    public function addHotel(Hotel $hotel): self
    {
        if (!$this->hotels->contains($hotel)) {
            $this->hotels[] = $hotel;
            $hotel->setEvent($this);
        }

        return $this;
    }

    public function removeHotel(Hotel $hotel): self
    {
        if ($this->hotels->contains($hotel)) {
            $this->hotels->removeElement($hotel);
            // set the owning side to null (unless already changed)
            if ($hotel->getEvent() === $this) {
                $hotel->setEvent(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Transport[]
     */
    public function getTransports(): Collection
    {
        return $this->transports;
    }

    public function addTransport(Transport $transport): self
    {
        if (!$this->transports->contains($transport)) {
            $this->transports[] = $transport;
            $transport->setEvent($this);
        }

        return $this;
    }

    public function removeTransport(Transport $transport): self
    {
        if ($this->transports->contains($transport)) {
            $this->transports->removeElement($transport);
            // set the owning side to null (unless already changed)
            if ($transport->getEvent() === $this) {
                $transport->setEvent(null);
            }
        }

        return $this;
    }
}
