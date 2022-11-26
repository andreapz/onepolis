<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoomMealRepository")
 */
class RoomMeal
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
    private $room;

    /**
     * @ORM\Column(type="integer")
     */
    private $meal;

    /**
     * @ORM\Column(type="integer")
     */
    private $event;
    
    /**
     * @ORM\Column(type="string", length=1)
     */
    private $status;
    
    
    public $STATUS_SELECTED = "s";
    public $STATUS_DISABLED = "d";
    public $STATUS_ENABLED = "e";
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoom(): ?int
    {
        return $this->room;
    }

    public function setRoom(int $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getMeal(): ?int
    {
        return $this->meal;
    }

    public function setMeal(int $meal): self
    {
        $this->meal = $meal;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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
    
    public static function checkRoomMeals($repository, $eventId) {
        $roomMeals = $repository->findSelected($eventId);
        $roomMealsArray = array();
        foreach ($roomMeals as $roomMeal) {
            if (!isset($roomMealsArray[$roomMeal->getRoom()])) {
                $roomMealsArray[$roomMeal->getRoom()] = array();
            }
            
            $roomMealsArray[$roomMeal->getRoom()][$roomMeal->getMeal()] = $roomMeal->getStatus();
        }
        return json_encode($roomMealsArray);
    }
}
