<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RestaurantMealRepository")
 */
class RestaurantMeal
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
     * @ORM\Column(type="datetime")
     */
    private $mealDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $total;

    /**
     * @ORM\ManyToOne(targetEntity="Restaurant", inversedBy="meals")
     * @ORM\JoinColumn(name="restaurant", referencedColumnName="id")
     */
    private $restaurant;
    
     /**
     * @ORM\OneToMany(targetEntity="RestaurantCost", cascade={"persist"}, mappedBy="meal")
    */
    private $tickets;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $eid;
    
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

    public function getMealDate(): ?\DateTimeInterface
    {
        return $this->mealDate;
    }

    public function setMealDate(\DateTimeInterface $mealDate): self
    {
        $this->mealDate = $mealDate;

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
    
    function getEid() {
        return $this->eid;
    }

    function setEid($eid) {
        $this->eid = $eid;
    }
    
    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }
    
    function getDateFormatted() {
        return $this->mealDate->format('d-m-Y');
    }
    
    /**
     * @return Collection|RestaurantCost[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(RestaurantCost $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setMeal($this);
        }

        return $this;
    }

    public function removeTicket(RestaurantCost $ticket): self
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if ($ticket->getMeal() === $this) {
                $ticket->setMeal(null);
            }
        }

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
    
    public function getFree($counts)
    {
        if (array_key_exists($this->getId(), $count)) {
            return $this->total - $count[$this->getId()];
        }
        
        return $this->total;
    } 
    
    public static function isMeal($meals, $mealId) {
        foreach ($meals as $meal) {
            if ($meal->getId() == $mealId) {
                return true;
            }
        }
        return false;
    }
    
    public static function retrieveMeal($meals, $mealId) {
        foreach ($meals as $meal) {
            if ($meal->getId() == $mealId) {
                return $meal;
            }
        }
        return null;
    }
    
    public static function checkMealsCount($repository, $eventId) {
        $mealsCount = $repository->findCountByEvent($eventId);
        $mealsCountArray = array();
        foreach ($mealsCount as $mealcount) {
            $mealsCountArray[$mealcount['id']]['id'] = $mealcount['id'];
            $mealsCountArray[$mealcount['id']]['c'] = $mealcount['rcount'];
            $mealsCountArray[$mealcount['id']]['t'] = $mealcount['total'];
        }
        return json_encode($mealsCountArray);
    }
}
