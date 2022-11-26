<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RestaurantMatchRepository")
 */
class RestaurantMatch
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $restaurantcost;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $mealreal;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $restaurant;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $uid;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $d;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $last;
    
    
    public function getId(): ?int
    {
        return $this->id;
    }
    

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getD(): ?int
    {
        return $this->d;
    }

    public function setD(int $d): self
    {
        $this->d = $d;

        return $this;
    }

    

    public function getLast(): ?\DateTimeInterface
    {
        return $this->last;
    }

    public function setLast(\DateTimeInterface $last): self
    {
        $this->last = $last;

        return $this;
    }



    public function getMealreal(): ?int
    {
        return $this->mealreal;
    }

    public function setMealreal(int $mealreal): self
    {
        $this->mealreal = $mealreal;

        return $this;
    }

    public function getRestaurantcost(): ?int
    {
        return $this->restaurantcost;
    }

    public function setRestaurantcost(int $restaurantcost): self
    {
        $this->restaurantcost = $restaurantcost;

        return $this;
    }

    public function getRestaurant(): ?int
    {
        return $this->restaurant;
    }

    public function setRestaurant(int $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }

   
    
    
}
