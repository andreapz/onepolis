<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoomRealRepository")
 */
class RoomReal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $floor;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $rooms;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $guests;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $bath;
    
    /**
     * @ORM\Column(type="integer", name="access")
     */
    private $accessible;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $single;
    
    /**
     * @ORM\Column(type="integer", name="doublebed")
     */
    private $double;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $twin;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $sofa;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $bunk;

    /**
     * @ORM\ManyToOne(targetEntity="HotelReal", inversedBy="rooms")
     * @ORM\JoinColumn(name="hotel_real", referencedColumnName="id")
     */
    private $hotel;
    
    /**
     * @ORM\OneToMany(targetEntity="RoomRealPrice", cascade={"persist"}, mappedBy="room")
    */
    private $costs;
    
    /**
     * @ORM\ManyToOne(targetEntity="RoomBase")
     * @ORM\JoinColumn(name="room_base", referencedColumnName="id")
     */
    private $room;

    public function __construct()
    {
        $this->costs = new ArrayCollection();
    }
    
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFloor(): ?int
    {
        return $this->floor;
    }

    public function setFloor(int $floor): self
    {
        $this->floor = $floor;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    public function getGuests(): ?int
    {
        return $this->guests;
    }

    public function setGuests(int $guests): self
    {
        $this->guests = $guests;

        return $this;
    }

    public function getBath(): ?bool
    {
        return $this->bath;
    }

    public function setBath(bool $bath): self
    {
        $this->bath = $bath;

        return $this;
    }

    public function getAccessible(): ?int
    {
        return $this->accessible;
    }

    public function setAccessible(int $accessible): self
    {
        $this->accessible = $accessible;

        return $this;
    }

    public function getSingle(): ?int
    {
        return $this->single;
    }

    public function setSingle(int $single): self
    {
        $this->single = $single;

        return $this;
    }

    public function getDouble(): ?int
    {
        return $this->double;
    }

    public function setDouble(int $double): self
    {
        $this->double = $double;

        return $this;
    }

    public function getTwin(): ?int
    {
        return $this->twin;
    }

    public function setTwin(int $twin): self
    {
        $this->twin = $twin;

        return $this;
    }

    public function getSofa(): ?int
    {
        return $this->sofa;
    }

    public function setSofa(int $sofa): self
    {
        $this->sofa = $sofa;

        return $this;
    }

    public function getBunk(): ?int
    {
        return $this->bunk;
    }

    public function setBunk(int $bunk): self
    {
        $this->bunk = $bunk;

        return $this;
    }

    /**
     * @return Collection|RoomRealPrice[]
     */
    public function getCosts(): Collection
    {
        return $this->costs;
    }

    public function addCost(RoomRealPrice $cost): self
    {
        if (!$this->costs->contains($cost)) {
            $this->costs[] = $cost;
            $cost->setRoomreal($this);
        }

        return $this;
    }

    public function removeCost(RoomRealPrice $cost): self
    {
        if ($this->costs->contains($cost)) {
            $this->costs->removeElement($cost);
            // set the owning side to null (unless already changed)
            if ($cost->getRoomreal() === $this) {
                $cost->setRoomreal(null);
            }
        }

        return $this;
    }

    public function getHotel(): ?HotelReal
    {
        return $this->hotel;
    }

    public function setHotel(?HotelReal $hotel): self
    {
        $this->hotel = $hotel;

        return $this;
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

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
    
    function getRoom() {
        return $this->room;
    }

    function setRoom($room) {
        $this->room = $room;
    }


}
