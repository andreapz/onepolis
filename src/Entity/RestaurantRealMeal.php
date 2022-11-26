<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RestaurantRealMealRepository")
 */
class RestaurantRealMeal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="RestaurantReal", inversedBy="meals")
     * @ORM\JoinColumn(name="restaurant_real", referencedColumnName="id")
     */
    private $restaurant;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $guests;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $rid;

    /**
     * @ORM\Column(type="integer")
     */
    private $mealid;

    /**
     * @ORM\OneToMany(targetEntity="RestaurantRealMealPrice", cascade={"persist"}, mappedBy="meal")
    */
    private $costs;

    public function __construct()
    {
        $this->costs = new ArrayCollection();
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

    public function getRestaurant(): ?RestaurantReal
    {
        return $this->restaurant;
    }

    public function setRestaurant(?RestaurantReal $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    /**
     * @return Collection|RestaurantRealMealPrice[]
     */
    public function getCosts(): Collection
    {
        return $this->costs;
    }

    public function addCost(RestaurantRealMealPrice $cost): self
    {
        if (!$this->costs->contains($cost)) {
            $this->costs[] = $cost;
            $cost->setMeal($this);
        }

        return $this;
    }

    public function removeCost(RestaurantRealMealPrice $cost): self
    {
        if ($this->costs->contains($cost)) {
            $this->costs->removeElement($cost);
            // set the owning side to null (unless already changed)
            if ($cost->getMeal() === $this) {
                $cost->setMeal(null);
            }
        }

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

    public function getRid(): ?int
    {
        return $this->rid;
    }

    public function setRid(int $rid): self
    {
        $this->rid = $rid;

        return $this;
    }

    public function getMealid(): ?int
    {
        return $this->mealid;
    }

    public function setMealid(int $mealid): self
    {
        $this->mealid = $mealid;

        return $this;
    }
}
