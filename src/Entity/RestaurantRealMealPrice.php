<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RestaurantRealMealPriceRepository")
 */
class RestaurantRealMealPrice
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
     * @ORM\ManyToOne(targetEntity="RestaurantRealMeal", inversedBy="costs")
     * @ORM\JoinColumn(name="restaurant_real_meal", referencedColumnName="id")
     */
    private $meal;
    
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

    public function getMeal(): ?RestaurantRealMeal
    {
        return $this->meal;
    }

    public function setMeal(?RestaurantRealMeal $meal): self
    {
        $this->meal = $meal;

        return $this;
    }
}
