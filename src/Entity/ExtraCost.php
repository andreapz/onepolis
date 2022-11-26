<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExtraCostRepository")
 */
class ExtraCost
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
     * @ORM\Column(type="string", length=30)
     */
    private $type;
    
    /**
     * @ORM\ManyToOne(targetEntity="HotelReal", inversedBy="rooms")
     * @ORM\JoinColumn(name="hotel_real", referencedColumnName="id")
     */
    private $hotel;
    
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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
}
