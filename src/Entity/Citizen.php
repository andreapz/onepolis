<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\CitizenRepository")
 */
class Citizen
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cityBirth;
    
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $birthDate;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $gender;

    /**
     * @ORM\Column(type="boolean")
     */
    private $needSupport;

    /**
     * @ORM\Column(type="boolean")
     */
    private $transport;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $roomNote;
    
    /**
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="citizens")
     * @ORM\JoinColumn(name="task", referencedColumnName="id")
     */
    private $task;
    
    /**
    * @ORM\OneToMany(targetEntity="RestaurantCostCitizen", cascade={"persist"}, mappedBy="citizen")
    */
    private $ticketsrestaurant;

    /**
    * @ORM\OneToMany(targetEntity="RoomCostCitizen", cascade={"persist"}, mappedBy="citizen")
    */
    private $ticketsroom;
    
    /**
    * @ORM\OneToMany(targetEntity="BusCostCitizen", cascade={"persist"}, mappedBy="citizen")
    */
    private $ticketsbus;
    
    /**
    * @ORM\OneToOne(targetEntity="Address",  cascade={"persist"})
    */
    private $address;

    /**
    * @ORM\OneToOne(targetEntity="TicketCostCitizen", cascade={"persist"}, mappedBy="citizen")
    */
    private $ticketsevent;

    /**
    * @ORM\ManyToOne(targetEntity="Branch",  cascade={"persist"})
    */
    private $branch;
    
    /**
    * @ORM\ManyToOne(targetEntity="Relationship",  cascade={"persist"})
    */
    private $relationship;

    /**
     * @ORM\Column(type="integer")
     */
    private $delegate;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $guest;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $partner;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $valid;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $first;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $eid;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $uid;

    /**
    * @ORM\OneToMany(targetEntity="CheckIn", cascade={"persist"}, mappedBy="citizen")
    * @ORM\OrderBy({"id" = "ASC"})
    */
    private $checkins;
    
    
    public function __construct()
    {
        $this->ticketsrestaurant = new ArrayCollection();
        $this->ticketsroom = new ArrayCollection();
        $this->checkins = new ArrayCollection();
        $this->ticketsbus = new ArrayCollection();
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

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }
    
    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): self
    {
        $this->task = $task;

        return $this;
    }

    /**
     * @return Collection|RestaurantCostCitizen[]
     */
    public function getTicketsrestaurant(): Collection
    {
        return $this->ticketsrestaurant;
    }

    public function addTicketsrestaurant(RestaurantCostCitizen $ticketsrestaurant): self
    {
        if (!$this->ticketsrestaurant->contains($ticketsrestaurant)) {
            $this->ticketsrestaurant[] = $ticketsrestaurant;
            $ticketsrestaurant->setCitizen($this);
            $ticketsrestaurant->setUpdateDate(new \DateTime());
        }

        return $this;
    }

    public function removeTicketsrestaurant(RestaurantCostCitizen $ticketsrestaurant): self
    {
        if ($this->ticketsrestaurant->contains($ticketsrestaurant)) {
            $this->ticketsrestaurant->removeElement($ticketsrestaurant);
            // set the owning side to null (unless already changed)
            if ($ticketsrestaurant->getCitizen() === $this) {
                $ticketsrestaurant->setCitizen(null);
                $ticketsrestaurant->setUpdateDate(new \DateTime());
            }
        }

        return $this;
    }
    
    public function removeAllTicketsrestaurant(): self
    {
        foreach ($this->ticketsrestaurant as $ticketsrestaurant) {
            if ($ticketsrestaurant->getCitizen() === $this) {
                $ticketsrestaurant->setCitizen(null);
                $ticketsrestaurant->setUpdateDate(new \DateTime());
            }
        }
       
        return $this;
    }
    
    public function removeAllTicketsrestaurantNotIncluded($mealsIds): self
    {
        //var_dump($mealsIds); die();
        foreach ($this->ticketsrestaurant as $ticketsrestaurant) {
            if (//is_null($ticketsrestaurant->getId()) && 
                    !in_array($ticketsrestaurant->getMid(), $mealsIds)) {
                $ticketsrestaurant->setCid($ticketsrestaurant->getCitizen()->getId());
                $ticketsrestaurant->setUpdateDate(new \DateTime());
                $ticketsrestaurant->setCitizen(null);
            }
        }
        
        return $this;
    }
    
    public function setTicketsrestaurant($ticketsrestaurant) {
        $this->ticketsrestaurant = $ticketsrestaurant;
    }

    /**
     * @return Collection|RoomCostCitizen[]
     */
    public function getTicketsroom(): Collection
    {
        return $this->ticketsroom;
    }

    public function removeAllTicketsroomNotIncluded($roomIds): self
    {
        //var_dump($mealsIds); die();
        foreach ($this->ticketsroom as $ticketsroom) {
            if (//is_null($ticketsrestaurant->getId()) && 
                    !in_array($ticketsroom->getRid(), $roomIds)) {
                $ticketsroom->setCid($ticketsroom->getCitizen()->getId());
                $ticketsroom->setUpdateDate(new \DateTime());
                $ticketsroom->setCitizen(null);
            }
        }
        
        return $this;
    }
    
    public function updateTicketRoom($newTicketsRoom, $roomIds): self
    {
        foreach ($this->ticketsroom as $ticketroom) {
            if ($ticketroom->getRid() != $roomIds) {
                $this->removeTicketsroom($ticketroom);
            }
        }
        
        foreach ($newTicketsRoom as $newTicketRoom) {
            
            $status = true;
            foreach ($this->ticketsroom as $ticketroom) {
                                
                if ($ticketroom->isNewPrice($newTicketRoom)) {
                    $this->removeTicketsroom($ticketroom);
                } else if ($ticketroom->isEqual($newTicketRoom)) {
                    $status = false;
                }
            }
            
            if ($status) {
                $this->addTicketsroom($newTicketRoom);
            }
        }
        
        return $this;
    }
    
    public function addTicketsroom(RoomCostCitizen $ticketsroom): self
    {
        if (!$this->ticketsroom->contains($ticketsroom)) {
            $this->ticketsroom[] = $ticketsroom;
            $ticketsroom->setCitizen($this);
        }

        return $this;
    }

    public function setTicketsroom($ticketsroom) {
        $this->ticketsroom = $ticketsroom;
    }
    
    public function removeTicketsroom(RoomCostCitizen $ticketsroom): self
    {
        if ($this->ticketsroom->contains($ticketsroom)) {
            $this->ticketsroom->removeElement($ticketsroom);
            // set the owning side to null (unless already changed)
            if ($ticketsroom->getCitizen() === $this) {
                $ticketsroom->setCitizen(null);
                $ticketsroom->setUpdateDate(new \DateTime());
            }
        }

        return $this;
    }
    
    public function removeAllTicketsroom(): self
    {
        foreach ($this->ticketsroom as $ticket) {
            if ($ticket->getCitizen() === $this) {
                $ticket->setCitizen(null);
                $ticketsroom->setUpdateDate(new \DateTime());
            }
        }
        
        return $this;
    }
    
    public function getMealTickets(int $mealId)
    {
        $mealtickets = new ArrayCollection();
        foreach ($this->getTicketsrestaurant() as $tr) {
            if ($tr->getMeal()->getId() == $mealId) {
                $tickets->add($tr);
            } 
        }
        return $mealtickets;
    }
    
    public function getRestaurantCostCitizenIds(int $mealId) {
        $tickets = array();
        foreach ($this->getTicketsrestaurant() as $tr) {
            if ($tr->getMid() == $mealId) {
                $tickets[] = $tr->getRestaurantcost()->getId();
            } 
        }
        return $tickets;
    }
    
    public function getRoomCostCitizenIds(int $roomId) {
        $tickets = array();
        foreach ($this->getTicketsroom() as $tr) {
            if ($tr->getRid() == $roomId) {
                $tickets[] = $tr->getRoomcost()->getId();
            } 
        }
        return $tickets;
    }
    
    public function getBusCostCitizenIds(int $busId) {
        $tickets = array();
        foreach ($this->getTicketsbus() as $tb) {
            if ($tb->getBid() == $busId) {
                $tickets[] = $tb->getBuscost()->getId();
            } 
        }
        return $tickets;
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getCityBirth(): ?string
    {
        return $this->cityBirth;
    }

    public function setCityBirth(?string $cityBirth): self
    {
        $this->cityBirth = $cityBirth;

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

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getNeedSupport(): ?bool
    {
        return $this->needSupport;
    }

    public function setNeedSupport(bool $needSupport): self
    {
        $this->needSupport = $needSupport;

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

    public function getBranch(): ?Branch
    {
        return $this->branch;
    }

    public function setBranch(?Branch $branch): self
    {
        $this->branch = $branch;

        return $this;
    }

    public function getRelationship(): ?Relationship
    {
        return $this->relationship;
    }

    public function setRelationship(?Relationship $relationship): self
    {
        $this->relationship = $relationship;

        return $this;
    }

    public function getDelegate(): ?int
    {
        return $this->delegate;
    }

    public function setDelegate(int $delegate): self
    {
        $this->delegate = $delegate;

        return $this;
    }

    public function getTicketsevent(): ?TicketCostCitizen
    {
        return $this->ticketsevent;
    }

    public function setTicketsevent(?TicketCostCitizen $ticketsevent): self
    {
        $this->ticketsevent = $ticketsevent;

        // set (or unset) the owning side of the relation if necessary
        $newCitizen = $ticketsevent === null ? null : $this;
        if ($newCitizen !== $ticketsevent->getCitizen()) {
            $ticketsevent->setCitizen($newCitizen);
        }

        return $this;
    }

    public function removeTicketEvent() {
        if (!empty($this->ticketsevent)) {
            $this->ticketsevent->setCitizen(null);
            $this->ticketsevent->setUpdateDate(new \DateTime());
            $this->ticketsevent = null;
        }

        return $this;
    }

    public function getGuest(): ?bool
    {
        return $this->guest;
    }

    public function setGuest(bool $guest): self
    {
        $this->guest = $guest;

        return $this;
    }

    public function getEid(): ?int
    {
        return $this->eid;
    }

    public function setEid(int $eid): self
    {
        $this->eid = $eid;

        return $this;
    }

    public function getTransport(): ?bool
    {
        return $this->transport;
    }

    public function setTransport(bool $transport): self
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * @return Collection|CheckIn[]
     */
    public function getCheckins(): Collection
    {
        return $this->checkins;
    }

    public function addCheckin(CheckIn $checkin): self
    {
        if (!$this->checkins->contains($checkin)) {
            $this->checkins[] = $checkin;
            $checkin->setCitizen($this);
        }

        return $this;
    }

    public function removeCheckin(CheckIn $checkin): self
    {
        if ($this->checkins->contains($checkin)) {
            $this->checkins->removeElement($checkin);
            // set the owning side to null (unless already changed)
            if ($checkin->getCitizen() === $this) {
                $checkin->setCitizen(null);
            }
        }

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

    /**
     * @return Collection|BusCostCitizen[]
     */
    public function getTicketsbus(): Collection
    {
        return $this->ticketsbus;
    }

    public function addTicketsbus(BusCostCitizen $ticketsbus): self
    {
        if (!$this->ticketsbus->contains($ticketsbus)) {
            $this->ticketsbus[] = $ticketsbus;
            $ticketsbus->setCitizen($this);
        }

        return $this;
    }

    public function removeTicketsbus(BusCostCitizen $ticketsbus): self
    {
        if ($this->ticketsbus->contains($ticketsbus)) {
            $this->ticketsbus->removeElement($ticketsbus);
            // set the owning side to null (unless already changed)
            if ($ticketsbus->getCitizen() === $this) {
                $ticketsbus->setUpdateDate(new \DateTime());
                $ticketsbus->setCitizen(null);
            }
        }

        return $this;
    }
    
    public function removeAllTicketsbusNotIncluded($busesIds): self
    {
        //var_dump($mealsIds); die();
        foreach ($this->ticketsbus as $ticketbus) {
            if (//is_null($ticketsrestaurant->getId()) && 
                    !in_array($ticketbus->getBid(), $busesIds)) {
                $ticketbus->setCid($ticketbus->getCitizen()->getId());
                $ticketbus->setUpdateDate(new \DateTime());
                $ticketbus->setCitizen(null);
            }
        }
        
        return $this;
    }

    public function getRoomNote(): ?string
    {
        return $this->roomNote;
    }

    public function setRoomNote(?string $roomNote): self
    {
        $this->roomNote = $roomNote;

        return $this;
    }

    public function getPartner(): ?bool
    {
        return $this->partner;
    }

    public function setPartner(bool $partner): self
    {
        $this->partner = $partner;

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }

    public function getFirst(): ?bool
    {
        return $this->first;
    }

    public function setFirst(bool $first): self
    {
        $this->first = $first;

        return $this;
    }

    public static function createNewTicketsRoom($citizen, $roomId, $rooms, 
            $userId, $cid, $now) {

        $usertickets = array();
        
        foreach ($rooms as $room) {
            foreach ($room->getTickets() as $ticket) {
                
                if (RoomCost::isTicketValid($ticket, $roomId, $now, $citizen->getBirthDate())) {
                           
                    $userticket = RoomCostCitizen::create($room->getEid(), 
                            $room->getParent()->getId(),$room->getId(), $ticket, 
                            $citizen, $userId, $cid);
                    
                    $usertickets[] = $userticket;
                }
            }
        }

        return $usertickets;
    }
    
    public function registerRoom($roomId, $rooms, $userId, 
                        $cid, $now) {
        $usertickets = Citizen::createNewTicketsRoom($this, 
                        $roomId, $rooms, $userId, 
                        $cid, $now);
        $this->updateTicketRoom($usertickets, $roomId);
    }
    
    public static function createNewTicketsMeal($eventId, $restaurantId, 
            $citizen, $meal, $userId, $cid, $now) {
        
        foreach ($meal->getTickets() as $ticket) {
            if (RestaurantCost::isTicketValid($ticket, 
                    $meal->getId(), $now, $citizen->getBirthDate())) {
                return RestaurantCostCitizen::create($meal, $ticket, 
                        $citizen, $eventId, $restaurantId, $userId, $cid);
            }
        }
        
        return null;
        
    }
    
    public static function createNewTicketsBus($eventId, $transportId, 
            $citizen, $bus, $userId, $cid, $now) {
        
        foreach ($bus->getTickets() as $ticket) {
            if (BusCost::isTicketValid($ticket, 
                    $bus->getId(), $now, $citizen->getBirthDate())) {
                return BusCostCitizen::create($bus, $ticket, 
                        $citizen, $eventId, $transportId, $userId, $cid);
            }
        }
        
        return null;
        
    }
    
    public function updateTicketsMeal($tickets) {
        
        foreach ($this->getTicketsrestaurant() as $ticketRestaurant) {
            if (isset($tickets[$ticketRestaurant->getMid()])) {
                $mid = $ticketRestaurant->getMid();
                $ticket = $tickets[$mid];
                
                if ($ticketRestaurant->isNewPrice($ticket)) {
                    $this->removeTicketsrestaurant($ticketRestaurant);
                } else {
                    unset($tickets[$mid]);
                } 
                
            } else {
                $this->removeTicketsrestaurant($ticketRestaurant);
            }
        }
        
        foreach ($tickets as $key => $ticket) {
            $this->addTicketsrestaurant($ticket);
        }
    }
    
    public function registerMeals($dataMeals, $meals, 
            $eventId, $userId, $cid, $now) {
        
        $ticketsMeal = array();
            
        foreach ($dataMeals as $mealForm) {

            $meal = RestaurantMeal::retrieveMeal($meals, $mealForm->getId());

            if (empty($meal)) {
                continue;
            }
            
            $ticketMeal = Citizen::createNewTicketsMeal($eventId, 
                    $meal->getRestaurant()->getId(), $this, $meal, 
                    $userId, $cid, $now);

            if (!empty($ticketMeal)) {
                $ticketsMeal[$mealForm->getId()] = $ticketMeal;   
            }
        }

        $this->updateTicketsMeal($ticketsMeal);
    }
    
    public function updateTicketsBus($tickets) {
        
        foreach ($this->getTicketsbus() as $ticketsbus) {
            if (isset($tickets[$ticketsbus->getBid()])) {
                $bid = $ticketsbus->getBid();
                $ticket = $tickets[$bid];
                
                if ($ticketsbus->isNewPrice($ticket)) {
                    $this->removeTicketsbus($ticketsbus);
                } else {
                    unset($tickets[$bid]);
                } 
            } else {
                $this->removeTicketsbus($ticketsbus);
            }
        }
        
        
        foreach ($tickets as $key => $ticket) {
            $this->addTicketsbus($ticket);
        }
    }
    
    public function registerBus($databus, $buses, $eventId,
                        $userId, $cid, $now) {
        $ticketsBus = array();
            
        foreach ($databus as $busForm) {

            $bus = Bus::retrieve($buses, $busForm->getId());

            if (empty($bus)) {
                continue;
            }
            
            $ticketBus = Citizen::createNewTicketsBus($eventId, 
                    $bus->getTransport()->getId(), $this, $bus, 
                    $userId, $cid, $now);

            if (!empty($ticketBus)) {
                $ticketsBus[$busForm->getId()] = $ticketBus;   
            }
            
        }

        $this->updateTicketsBus($ticketsBus);
    }


    public function registerTicketEvent($ticketTypes, $ticketId, $eventId,
            $userId, $cid, $now) {
    
        $ticketType = TicketType::retrieve($ticketTypes, $ticketId);
        
        if (empty($ticketType)) {
            return;
        }
        
        $ticket = Citizen::createNewTicketEvent($eventId,
            $this, $ticketType, $userId,
            $cid, $now);

        if (!empty($ticket)) {
            $this->updateTicketEvent($ticket);
        }
    }
    
    public static function createNewTicketEvent($eventId, 
            $citizen, $ticketType, $userId, $cid, $now) {
        
        //var_dump(count$ticketType); die();
        
        foreach ($ticketType->getTickets() as $ticket) {
            if (TicketCost::isTicketValid($ticket, 
                    $ticketType->getId(), $now, $citizen->getBirthDate())) {
                return TicketCostCitizen::create($eventId, $ticketType->getId(), 
                        $ticket, $citizen, $userId, $cid);
            }
        }
       
        return null;    
    }
    
    public function updateTicketEvent($ticket) {
        
        if (empty($this->getTicketsevent())) {
            $this->setTicketsevent($ticket);
        } else if ($this->getTicketsevent()->getTtid() != $ticket->getTtid()) {
            $this->removeTicketEvent();
            $this->setTicketsevent($ticket);
        } else if ($this->getTicketsevent()->isNewPrice($ticket)) {
            $this->removeTicketEvent();
            $this->setTicketsevent($ticket);
        } 
    }
    
    

}
