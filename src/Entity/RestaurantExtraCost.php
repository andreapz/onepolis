<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RestaurantExtraCostRepository")
 */
class RestaurantExtraCost
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
     * @ORM\ManyToOne(targetEntity="RestaurantReal", inversedBy="extras")
     * @ORM\JoinColumn(name="restaurant_real", referencedColumnName="id")
     */
    private $restaurant;
    
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

    public function getRestaurant(): ?RestaurantReal
    {
        return $this->restaurant;
    }

    public function setRestaurant(?RestaurantReal $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    
}
