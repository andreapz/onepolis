<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoomRealPriceRepository")
 */
class RoomRealPrice
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
    private $price;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $guests;

     /**
     * @ORM\ManyToOne(targetEntity="RoomReal", inversedBy="costs")
     * @ORM\JoinColumn(name="room_real", referencedColumnName="id")
     */
    private $room;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price): self
    {
        $this->price = $price;

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

    public function getRoom(): ?RoomReal
    {
        return $this->room;
    }

    public function setRoom(?RoomReal $room): self
    {
        $this->room = $room;

        return $this;
    }
}
