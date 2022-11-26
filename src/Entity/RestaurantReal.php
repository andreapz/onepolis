<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RestaurantRealRepository")
 */
class RestaurantReal
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $surname;
    
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $email;
    
    /**
    * @ORM\OneToOne(targetEntity="Address",  cascade={"persist"})
    */
    private $address;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $latitude;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $longitude;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $event;
    
    /**
     * @ORM\OneToMany(targetEntity="RestaurantRealMeal", cascade={"persist"}, mappedBy="restaurant")
    */
    private $meals;
    
    /**
     * @ORM\OneToMany(targetEntity="RestaurantExtraCost", cascade={"persist"}, mappedBy="restaurant")
    */
    private $extras;
    
    /**
     * @ORM\ManyToOne(targetEntity="Restaurant")
     * @ORM\JoinColumn(name="restaurant", referencedColumnName="id")
     */
    private $restaurant;

    public function __construct()
    {
        $this->meals = new ArrayCollection();
        $this->extras = new ArrayCollection();
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getEvent(): ?int
    {
        return $this->event;
    }

    public function setEvent(int $event): self
    {
        $this->event = $event;

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
     * @return Collection|RestaurantRealMeal[]
     */
    public function getMeals(): Collection
    {
        return $this->meals;
    }

    public function addMeal(RestaurantRealMeal $meal): self
    {
        if (!$this->meals->contains($meal)) {
            $this->meals[] = $meal;
            $meal->setRestaurantreal($this);
        }

        return $this;
    }

    public function removeMeal(RestaurantRealMeal $meal): self
    {
        if ($this->meals->contains($meal)) {
            $this->meals->removeElement($meal);
            // set the owning side to null (unless already changed)
            if ($meal->getRestaurantreal() === $this) {
                $meal->setRestaurantreal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RestaurantExtraCost[]
     */
    public function getExtras(): Collection
    {
        return $this->extras;
    }

    public function addExtra(RestaurantExtraCost $extra): self
    {
        if (!$this->extras->contains($extra)) {
            $this->extras[] = $extra;
            $extra->setRestaurantreal($this);
        }

        return $this;
    }

    public function removeExtra(RestaurantExtraCost $extra): self
    {
        if ($this->extras->contains($extra)) {
            $this->extras->removeElement($extra);
            // set the owning side to null (unless already changed)
            if ($extra->getRestaurantreal() === $this) {
                $extra->setRestaurantreal(null);
            }
        }

        return $this;
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

    
}
