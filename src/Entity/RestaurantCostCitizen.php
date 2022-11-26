<?php

namespace App\Entity;

use App\Entity\Citizen;
use App\Entity\RestaurantCost;
use App\Entity\RestaurantMeal;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RestaurantCostCitizenRepository")
 */
class RestaurantCostCitizen
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
     * @ORM\Column(type="string", length=255)
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $bookDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updateDate;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $orderDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $event;

    /**
     * @ORM\Column(type="integer")
     */
    private $uid;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $mid;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $cid;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $restaurant;
    
    /**
     * @ORM\ManyToOne(targetEntity="RestaurantCost")
     * @ORM\JoinColumn(name="restaurant_cost", referencedColumnName="id")
     */
    private $restaurantcost;
    
    /**
     * @ORM\ManyToOne(targetEntity="Citizen", inversedBy="ticketsrestaurant")
     * @ORM\JoinColumn(name="citizen", referencedColumnName="id")
     */
    private $citizen;

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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBookDate(): ?\DateTimeInterface
    {
        return $this->bookDate;
    }

    public function setBookDate(\DateTimeInterface $bookDate): self
    {
        $this->bookDate = $bookDate;

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

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeInterface $orderDate): self
    {
        $this->orderDate = $orderDate;

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

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getRestaurantcost(): ?RestaurantCost
    {
        return $this->restaurantcost;
    }

    public function setRestaurantcost(?RestaurantCost $restaurantcost): self
    {
        $this->restaurantcost = $restaurantcost;

        return $this;
    }

    public function getCitizen(): ?Citizen
    {
        return $this->citizen;
    }

    public function setCitizen(?Citizen $citizen): self
    {
        $this->citizen = $citizen;

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
    }

    public function setUpdateDate(\DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    public function getMid(): ?int
    {
        return $this->mid;
    }

    public function setMid(int $mid): self
    {
        $this->mid = $mid;

        return $this;
    }

    public function getCid(): ?int
    {
        return $this->cid;
    }

    public function setCid(int $cid): self
    {
        $this->cid = $cid;

        return $this;
    }

    public static function create(RestaurantMeal $meal, RestaurantCost $ticket,
        Citizen $citizen, $eventId, $restaurantId, $userId, $citizenId){
        $userticket = new RestaurantCostCitizen();
        $userticket->setName($ticket->getName());
        $userticket->setPrice($ticket->getPrice());
        $userticket->setBookDate($meal->getMealDate());
        $userticket->setType($ticket->getType());
        $userticket->setOrderDate(new \DateTime());
        $userticket->setUid($userId);
        $userticket->setRestaurantcost($ticket);
        $userticket->setCitizen($citizen);
        $userticket->setEvent($eventId);
        $userticket->setUpdateDate(new \DateTime());
        $userticket->setRestaurant($restaurantId);
        //set Meal ID
        $userticket->setMid($meal->getId());
        //set Citizen ID
        $userticket->setCid($citizenId);
        return $userticket;
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
    
    public function isNewPrice($ticket) {
        return $this->getPrice() != $ticket->getPrice()
                && $this->getMid() == $ticket->getMid();
    }
}
